# Rate Limiting System Documentation

## Overview

This system prevents API rate limiting by implementing request debouncing, caching, retry mechanisms, and request queuing. It's designed to handle Laravel's throttle middleware gracefully while maintaining good user experience.

## Features

### 1. Request Debouncing
- Prevents rapid successive API calls
- Configurable delay per request type
- Automatically cancels previous requests when new ones are made

### 2. Intelligent Caching
- Long-term caching for static content (24 hours)
- Short-term caching for dynamic content (2-10 minutes)
- Cache invalidation and fallback strategies

### 3. Retry with Exponential Backoff
- Automatic retries for rate-limited requests (429 errors)
- Exponential backoff with jitter to prevent thundering herd
- Smart retry limits based on request criticality

### 4. Request Queue Management
- Limits concurrent requests (default: 3)
- Queues additional requests to prevent overwhelming the server
- Priority handling for critical operations

### 5. Request Deduplication
- Prevents identical requests from running simultaneously
- Returns the same promise for duplicate requests
- Reduces unnecessary API calls

## Configuration

### Environment Variables

```env
# Enable/disable rate limiting
NEXT_PUBLIC_RATE_LIMIT_ENABLED=true
NEXT_PUBLIC_RATE_LIMIT_DEBUG=true

# Cache settings (in minutes)
NEXT_PUBLIC_STATIC_CACHE_TTL=1440
NEXT_PUBLIC_PRODUCTS_CACHE_TTL=10
NEXT_PUBLIC_SITE_CONFIG_CACHE_TTL=60

# Request limits
NEXT_PUBLIC_MAX_CONCURRENT_REQUESTS=3
NEXT_PUBLIC_DEFAULT_DEBOUNCE_MS=500
NEXT_PUBLIC_MAX_RETRIES=3
```

### Request Type Configurations

```typescript
const RATE_LIMIT_CONFIG = {
  STATIC: {
    debounce: 1000,
    cacheTTL: 24 * 60 * 60 * 1000, // 24 hours
    maxRetries: 2
  },
  PRODUCTS: {
    debounce: 800,
    cacheTTL: 10 * 60 * 1000, // 10 minutes
    maxRetries: 2
  },
  USER_DATA: {
    debounce: 300,
    cacheTTL: 2 * 60 * 1000, // 2 minutes
    maxRetries: 2
  },
  CRITICAL: {
    debounce: 1000,
    cacheTTL: 0, // No cache
    maxRetries: 3
  }
};
```

## Usage

### In Hooks

```typescript
// Automatic rate limiting with configuration
export const useProducts = (params) => {
  const fetchProducts = async () => {
    // Your API call logic
  };

  return useRateLimitAware(
    'products-key',
    fetchProducts,
    [dependencies],
    getAdjustedConfig('PRODUCTS')
  );
};
```

### In Components

```typescript
// Manual rate limiting for specific operations
const handleSubmit = async () => {
  try {
    const result = await safeRequest(
      'submit-form',
      () => apiClient.submitForm(data),
      getAdjustedConfig('CRITICAL')
    );
    // Handle success
  } catch (error) {
    // Handle error
  }
};
```

## Monitoring

### Debug Component

The system includes a debug component that shows:
- Total API requests
- Cache hit rate
- Rate limit hits
- Failure rate
- Retry attempts

```tsx
<RateLimitDebug 
  enabled={process.env.NODE_ENV === 'development'}
  position="bottom-right"
  showDetailed={true}
/>
```

### Programmatic Access

```typescript
import { RateLimitMonitor } from '@/lib/rateLimitConfig';

// Get current stats
const stats = RateLimitMonitor.getStats();
console.log('Cache hit rate:', stats.cacheHitRate);
console.log('Rate limit hits:', stats.rateLimitHits);
```

## Best Practices

### 1. Choose Appropriate Cache TTL
- Static content: 24+ hours
- Product data: 10-15 minutes
- User data: 2-5 minutes
- Real-time data: No cache

### 2. Set Reasonable Debounce Times
- Search: 1200ms
- Form updates: 500ms
- Data fetching: 300ms
- Critical operations: 1000ms

### 3. Handle Errors Gracefully
```typescript
const { data, loading, error, retryCount } = useProducts(params);

if (error && retryCount > 0) {
  return <div>Retrying... ({retryCount}/3)</div>;
}
```

### 4. Use Appropriate Request Types
- `STATIC` for unchanging content
- `PRODUCTS` for catalog data
- `USER_DATA` for cart, orders
- `CRITICAL` for order creation, payments
- `SEARCH` for search operations

## Troubleshooting

### High Rate Limit Hits
1. Check debounce settings
2. Verify cache configuration
3. Review request patterns
4. Consider increasing cache TTL

### Poor Performance
1. Monitor cache hit rates
2. Adjust debounce timing
3. Review concurrent request limits
4. Check for request loops

### Failed Requests
1. Check retry configuration
2. Verify error handling
3. Monitor network conditions
4. Review API response patterns

## Architecture

### Request Flow
1. Request initiated
2. Debouncing applied
3. Deduplication check
4. Cache lookup
5. Queue management
6. API call with retry logic
7. Response caching
8. Monitoring update

### Components
- `requestManager.ts`: Core rate limiting logic
- `rateLimitConfig.ts`: Configuration and monitoring
- `RateLimitDebug.tsx`: Debug component
- `useApi.ts`: Rate-limited hooks

### Integration Points
- All API hooks use rate limiting
- Cache system integrated
- Error boundaries handle failures
- Development tools for debugging

## Migration Guide

### From Old Hooks
```typescript
// Old approach
const [data, setData] = useState(null);
useEffect(() => {
  apiCall().then(setData);
}, []);

// New approach
const { data, loading, error } = useRateLimitAware(
  'data-key',
  apiCall,
  [],
  getAdjustedConfig('PRODUCTS')
);
```

### Configuration Updates
1. Add environment variables
2. Update hook implementations
3. Add debug component
4. Configure monitoring

This system ensures robust API communication while preventing rate limiting issues and maintaining optimal user experience.