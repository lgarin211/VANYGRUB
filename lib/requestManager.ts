// Request management utilities to prevent rate limiting
import { withCache } from './cache';
import { RateLimitMonitor } from './rateLimitConfig';

// Request debouncing to prevent multiple rapid requests
const requestDebounce = new Map<string, NodeJS.Timeout>();

export const debounceRequest = <T>(
  key: string,
  fn: () => Promise<T>,
  delay: number = 500
): Promise<T> => {
  return new Promise((resolve, reject) => {
    // Clear existing timeout
    if (requestDebounce.has(key)) {
      clearTimeout(requestDebounce.get(key)!);
    }

    // Set new timeout
    const timeout = setTimeout(async () => {
      try {
        const result = await fn();
        resolve(result);
        requestDebounce.delete(key);
      } catch (error) {
        reject(error);
        requestDebounce.delete(key);
      }
    }, delay);

    requestDebounce.set(key, timeout);
  });
};

// Request queue to prevent concurrent requests
class RequestQueue {
  private queue: Array<() => Promise<any>> = [];
  private processing = false;
  private maxConcurrent = 3;
  private activeRequests = 0;

  async add<T>(fn: () => Promise<T>): Promise<T> {
    return new Promise((resolve, reject) => {
      this.queue.push(async () => {
        try {
          const result = await fn();
          resolve(result);
        } catch (error) {
          reject(error);
        }
      });

      this.process();
    });
  }

  private async process() {
    if (this.processing || this.activeRequests >= this.maxConcurrent) {
      return;
    }

    this.processing = true;

    while (this.queue.length > 0 && this.activeRequests < this.maxConcurrent) {
      const request = this.queue.shift();
      if (request) {
        this.activeRequests++;
        request().finally(() => {
          this.activeRequests--;
          this.process();
        });
      }
    }

    this.processing = false;
  }
}

// Global request queue instance
export const requestQueue = new RequestQueue();

// Retry with exponential backoff
export const retryWithBackoff = async <T>(
  fn: () => Promise<T>,
  maxRetries: number = 3,
  baseDelay: number = 1000
): Promise<T> => {
  let lastError: Error;

  for (let attempt = 0; attempt <= maxRetries; attempt++) {
    try {
      return await fn();
    } catch (error: any) {
      lastError = error;
      
      // Record retry attempt
      if (attempt > 0) {
        RateLimitMonitor.recordRetry();
      }
      
      // Don't retry on rate limiting errors immediately
      if (error.status === 429 || error.message?.includes('Too Many Attempts')) {
        RateLimitMonitor.recordRateLimit();
        const delay = baseDelay * Math.pow(2, attempt) + Math.random() * 1000;
        console.warn(`Rate limited, waiting ${Math.round(delay)}ms before retry ${attempt + 1}/${maxRetries + 1}`);
        
        if (attempt < maxRetries) {
          await new Promise(resolve => setTimeout(resolve, delay));
        }
      } else {
        // Don't retry on other errors
        RateLimitMonitor.recordFailure();
        throw error;
      }
    }
  }

  RateLimitMonitor.recordFailure();
  throw lastError!;
};

// Request deduplication - prevent duplicate requests
const activeRequests = new Map<string, Promise<any>>();

export const dedupeRequest = async <T>(
  key: string,
  fn: () => Promise<T>
): Promise<T> => {
  // If same request is already in progress, return that promise
  if (activeRequests.has(key)) {
    return activeRequests.get(key) as Promise<T>;
  }

  // Create new request
  const promise = fn().finally(() => {
    activeRequests.delete(key);
  });

  activeRequests.set(key, promise);
  return promise;
};

// Combined request wrapper with all protections
export const safeRequest = async <T>(
  key: string,
  fn: () => Promise<T>,
  options: {
    debounce?: number;
    cache?: boolean;
    cacheTTL?: number;
    retry?: boolean;
    maxRetries?: number;
    queue?: boolean;
    dedupe?: boolean;
  } = {}
): Promise<T> => {
  const {
    debounce = 300,
    cache = true,
    cacheTTL = 5 * 60 * 1000, // 5 minutes
    retry = true,
    maxRetries = 3,
    queue = true,
    dedupe = true
  } = options;

  // Record request start
  RateLimitMonitor.recordRequest();

  let requestFn = fn;

  // Apply caching
  if (cache) {
    requestFn = async () => {
      try {
        const result = await withCache(key, fn, cacheTTL) as Promise<T>;
        // Check if this was a cache hit (simplified check)
        RateLimitMonitor.recordCacheHit();
        return result;
      } catch (error) {
        return fn(); // Fallback to actual request
      }
    };
  }

  // Apply retry logic
  if (retry) {
    const originalFn = requestFn;
    requestFn = () => retryWithBackoff(originalFn, maxRetries);
  }

  // Apply deduplication
  if (dedupe) {
    const originalFn = requestFn;
    requestFn = () => dedupeRequest(key, originalFn);
  }

  // Apply queueing
  if (queue) {
    const originalFn = requestFn;
    requestFn = () => requestQueue.add(originalFn);
  }

  // Apply debouncing
  if (debounce > 0) {
    return debounceRequest(key, requestFn, debounce);
  }

  return requestFn();
};

// Rate limit aware hook wrapper
export const useRateLimitAware = <T>(
  key: string,
  apiCall: () => Promise<T>,
  dependencies: any[] = [],
  options?: Parameters<typeof safeRequest>[2]
) => {
  const [data, setData] = useState<T | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [retryCount, setRetryCount] = useState(0);

  const fetchData = useCallback(async () => {
    try {
      setLoading(true);
      setError(null);
      
      const result = await safeRequest(
        `${key}-${dependencies.join('-')}`,
        apiCall,
        {
          debounce: 500,
          cache: true,
          cacheTTL: 10 * 60 * 1000, // 10 minutes for rate limited endpoints
          retry: true,
          maxRetries: 2,
          ...options
        }
      );
      
      setData(result);
      setRetryCount(0);
    } catch (err: any) {
      console.error(`Request failed for ${key}:`, err);
      setError(err.message || 'Request failed');
      
      // Auto-retry with longer delay for rate limit errors
      if (err.status === 429 || err.message?.includes('Too Many Attempts')) {
        setRetryCount(prev => prev + 1);
        if (retryCount < 3) {
          setTimeout(() => {
            fetchData();
          }, Math.min(5000 * Math.pow(2, retryCount), 30000)); // Max 30 seconds
        }
      }
    } finally {
      setLoading(false);
    }
  }, [key, apiCall, ...dependencies, retryCount]);

  useEffect(() => {
    fetchData();
  }, [fetchData]);

  return {
    data,
    loading,
    error,
    refetch: fetchData,
    retryCount
  };
};

import { useState, useEffect, useCallback } from 'react';