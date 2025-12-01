// Cache utility for managing client-side caching
interface CacheItem {
  data: any;
  timestamp: number;
  expiry: number;
}

class CacheManager {
  private static instance: CacheManager;
  private cache: Map<string, CacheItem> = new Map();
  
  static getInstance(): CacheManager {
    if (!CacheManager.instance) {
      CacheManager.instance = new CacheManager();
    }
    return CacheManager.instance;
  }

  // Get cache configuration from environment variables
  private getCacheConfig() {
    return {
      imageCacheTTL: parseInt(process.env.NEXT_PUBLIC_IMAGE_CACHE_TTL || '60') * 60 * 1000, // Convert minutes to ms
      apiCacheTTL: parseInt(process.env.NEXT_PUBLIC_API_CACHE_TTL || '30') * 60 * 1000,
      staticCacheTTL: parseInt(process.env.NEXT_PUBLIC_STATIC_CACHE_TTL || '1440') * 60 * 1000,
      productsCacheTTL: parseInt(process.env.NEXT_PUBLIC_PRODUCTS_CACHE_TTL || '60') * 60 * 1000
    };
  }

  // Set cache with custom TTL
  set(key: string, data: any, customTTL?: number): void {
    const config = this.getCacheConfig();
    const ttl = customTTL || config.apiCacheTTL;
    
    const cacheItem: CacheItem = {
      data,
      timestamp: Date.now(),
      expiry: Date.now() + ttl
    };

    this.cache.set(key, cacheItem);
    
    // Store in localStorage for persistence
    if (typeof window !== 'undefined') {
      try {
        localStorage.setItem(`cache_${key}`, JSON.stringify(cacheItem));
      } catch (error) {
        console.warn('Cache: Failed to store in localStorage:', error);
      }
    }
  }

  // Get cache item
  get(key: string): any | null {
    let cacheItem = this.cache.get(key);

    // Try to get from localStorage if not in memory
    if (!cacheItem && typeof window !== 'undefined') {
      try {
        const stored = localStorage.getItem(`cache_${key}`);
        if (stored) {
          cacheItem = JSON.parse(stored);
          if (cacheItem) {
            this.cache.set(key, cacheItem);
          }
        }
      } catch (error) {
        console.warn('Cache: Failed to retrieve from localStorage:', error);
      }
    }

    if (!cacheItem) {
      return null;
    }

    // Check if cache has expired
    if (Date.now() > cacheItem.expiry) {
      this.delete(key);
      return null;
    }

    return cacheItem.data;
  }

  // Delete cache item
  delete(key: string): void {
    this.cache.delete(key);
    if (typeof window !== 'undefined') {
      localStorage.removeItem(`cache_${key}`);
    }
  }

  // Clear all cache
  clear(): void {
    this.cache.clear();
    if (typeof window !== 'undefined') {
      const keys = Object.keys(localStorage);
      keys.forEach(key => {
        if (key.startsWith('cache_')) {
          localStorage.removeItem(key);
        }
      });
    }
  }

  // Check if cache exists and is valid
  has(key: string): boolean {
    return this.get(key) !== null;
  }

  // Get cache info for debugging
  getInfo(key: string): any {
    const cacheItem = this.cache.get(key);
    if (!cacheItem) return null;

    return {
      key,
      size: JSON.stringify(cacheItem.data).length,
      created: new Date(cacheItem.timestamp),
      expires: new Date(cacheItem.expiry),
      isExpired: Date.now() > cacheItem.expiry
    };
  }

  // Clean expired cache items
  cleanup(): void {
    const now = Date.now();
    const expiredKeys: string[] = [];

    this.cache.forEach((item, key) => {
      if (now > item.expiry) {
        expiredKeys.push(key);
      }
    });

    expiredKeys.forEach(key => this.delete(key));
  }
}

// Cache wrapper for API calls
export const withCache = async <T>(
  key: string,
  fetcher: () => Promise<T>,
  customTTL?: number
): Promise<T> => {
  const cache = CacheManager.getInstance();
  
  // Try to get from cache first
  const cached = cache.get(key);
  if (cached) {
    console.log(`Cache hit for key: ${key}`);
    return cached;
  }

  console.log(`Cache miss for key: ${key}, fetching...`);
  
  try {
    const data = await fetcher();
    cache.set(key, data, customTTL);
    return data;
  } catch (error) {
    console.error(`Failed to fetch data for key: ${key}`, error);
    throw error;
  }
};

// Image cache preloader
export const preloadImage = (src: string): Promise<void> => {
  return new Promise((resolve, reject) => {
    const cache = CacheManager.getInstance();
    const config = cache['getCacheConfig']();
    const cacheKey = `image_${src}`;

    // Check if already cached
    if (cache.has(cacheKey)) {
      resolve();
      return;
    }

    const img = new Image();
    img.onload = () => {
      // Cache the successful load
      cache.set(cacheKey, { loaded: true, src }, config.imageCacheTTL);
      resolve();
    };
    img.onerror = reject;
    img.src = src;
  });
};

// Batch image preloader
export const preloadImages = async (images: string[]): Promise<void> => {
  const promises = images.map(src => preloadImage(src).catch(console.warn));
  await Promise.allSettled(promises);
};

export default CacheManager;