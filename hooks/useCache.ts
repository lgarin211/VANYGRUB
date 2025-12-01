import { useEffect } from 'react';
import CacheManager from '../lib/cache';

// Hook for automatic cache cleanup
export const useCacheCleanup = (intervalMs: number = 300000) => { // Default 5 minutes
  useEffect(() => {
    const cache = CacheManager.getInstance();
    
    // Initial cleanup
    cache.cleanup();
    
    // Set up interval for regular cleanup
    const interval = setInterval(() => {
      cache.cleanup();
      console.log('Cache cleanup performed');
    }, intervalMs);

    return () => clearInterval(interval);
  }, [intervalMs]);
};

// Hook for cache warming - preload critical data
export const useCacheWarming = (keys: string[], fetchers: (() => Promise<any>)[], ttl?: number) => {
  useEffect(() => {
    const cache = CacheManager.getInstance();
    
    keys.forEach((key, index) => {
      // Check if data is already cached
      if (!cache.has(key) && fetchers[index]) {
        // Warm up the cache
        fetchers[index]()
          .then(data => {
            cache.set(key, data, ttl);
            console.log(`Cache warmed for key: ${key}`);
          })
          .catch(error => {
            console.warn(`Failed to warm cache for key: ${key}`, error);
          });
      }
    });
  }, [keys, fetchers, ttl]);
};