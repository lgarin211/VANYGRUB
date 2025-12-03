// Rate limiting configuration for the application
export const RATE_LIMIT_CONFIG = {
  // Default settings for different types of requests
  DEFAULT: {
    debounce: 500,
    cacheTTL: 5 * 60 * 1000, // 5 minutes
    maxRetries: 2,
    retry: true,
    queue: true,
    dedupe: true
  },

  // Static content that changes rarely
  STATIC: {
    debounce: 1000,
    cacheTTL: 24 * 60 * 60 * 1000, // 24 hours
    maxRetries: 2,
    retry: true,
    queue: false, // Static content doesn't need queuing
    dedupe: true
  },

  // Product listings and searches
  PRODUCTS: {
    debounce: 800,
    cacheTTL: 10 * 60 * 1000, // 10 minutes
    maxRetries: 2,
    retry: true,
    queue: true,
    dedupe: true
  },

  // User-specific data like cart
  USER_DATA: {
    debounce: 300,
    cacheTTL: 2 * 60 * 1000, // 2 minutes
    maxRetries: 2,
    retry: true,
    queue: true,
    dedupe: true
  },

  // Critical operations like order creation
  CRITICAL: {
    debounce: 1000,
    cacheTTL: 0, // No cache for critical operations
    maxRetries: 3,
    retry: true,
    queue: true,
    dedupe: true
  },

  // Configuration data
  CONFIG: {
    debounce: 1000,
    cacheTTL: 60 * 60 * 1000, // 1 hour
    maxRetries: 2,
    retry: true,
    queue: false,
    dedupe: true
  },

  // Search operations
  SEARCH: {
    debounce: 1200, // Longer debounce for search
    cacheTTL: 5 * 60 * 1000, // 5 minutes
    maxRetries: 1, // Less retries for search
    retry: true,
    queue: true,
    dedupe: true
  }
};

// Request types mapping to configurations
export const REQUEST_TYPE = {
  HOME_DATA: 'STATIC',
  PRODUCTS: 'PRODUCTS',
  PRODUCT_DETAIL: 'PRODUCTS',
  FEATURED_PRODUCTS: 'PRODUCTS',
  CATEGORIES: 'CONFIG',
  SITE_CONFIG: 'CONFIG',
  CART: 'USER_DATA',
  ORDERS: 'USER_DATA',
  ORDER_CREATE: 'CRITICAL',
  ORDER_TRACKING: 'USER_DATA',
  TRANSACTIONS: 'USER_DATA',
  SEARCH: 'SEARCH'
} as const;

// Helper function to get configuration for a request type
export const getRateLimitConfig = (requestType: keyof typeof REQUEST_TYPE) => {
  const configKey = REQUEST_TYPE[requestType] || 'DEFAULT';
  return RATE_LIMIT_CONFIG[configKey as keyof typeof RATE_LIMIT_CONFIG];
};

// Environment-based overrides
export const getEnvironmentConfig = () => {
  const isDev = process.env.NODE_ENV === 'development';
  const isTest = process.env.NODE_ENV === 'test';
  
  if (isDev || isTest) {
    // More lenient settings for development
    return {
      debounceMultiplier: 0.5, // Shorter debounce in dev
      cacheMultiplier: 0.2, // Shorter cache in dev
      maxRetriesMultiplier: 0.5 // Fewer retries in dev
    };
  }
  
  return {
    debounceMultiplier: 1,
    cacheMultiplier: 1,
    maxRetriesMultiplier: 1
  };
};

// Apply environment-based adjustments
export const getAdjustedConfig = (requestType: keyof typeof REQUEST_TYPE) => {
  const baseConfig = getRateLimitConfig(requestType);
  const envConfig = getEnvironmentConfig();
  
  return {
    ...baseConfig,
    debounce: Math.round(baseConfig.debounce * envConfig.debounceMultiplier),
    cacheTTL: Math.round(baseConfig.cacheTTL * envConfig.cacheMultiplier),
    maxRetries: Math.max(1, Math.round(baseConfig.maxRetries * envConfig.maxRetriesMultiplier))
  };
};

// Rate limiting status monitoring
export const RateLimitMonitor = {
  stats: {
    totalRequests: 0,
    rateLimitHits: 0,
    cacheHits: 0,
    retries: 0,
    failures: 0
  },
  
  recordRequest() {
    this.stats.totalRequests++;
  },
  
  recordRateLimit() {
    this.stats.rateLimitHits++;
  },
  
  recordCacheHit() {
    this.stats.cacheHits++;
  },
  
  recordRetry() {
    this.stats.retries++;
  },
  
  recordFailure() {
    this.stats.failures++;
  },
  
  getStats() {
    return {
      ...this.stats,
      cacheHitRate: this.stats.totalRequests ? (this.stats.cacheHits / this.stats.totalRequests * 100).toFixed(2) + '%' : '0%',
      rateLimitRate: this.stats.totalRequests ? (this.stats.rateLimitHits / this.stats.totalRequests * 100).toFixed(2) + '%' : '0%',
      failureRate: this.stats.totalRequests ? (this.stats.failures / this.stats.totalRequests * 100).toFixed(2) + '%' : '0%'
    };
  },
  
  reset() {
    this.stats = {
      totalRequests: 0,
      rateLimitHits: 0,
      cacheHits: 0,
      retries: 0,
      failures: 0
    };
  }
};