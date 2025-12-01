'use client';

import { useState, useEffect } from 'react';
import CacheManager from '../lib/cache';

interface CacheInfo {
  key: string;
  size: number;
  created: Date;
  expires: Date;
  isExpired: boolean;
}

const CacheDebugger: React.FC<{ enabled?: boolean }> = ({ enabled = false }) => {
  const [cacheInfo, setCacheInfo] = useState<CacheInfo[]>([]);
  const [isVisible, setIsVisible] = useState(false);

  useEffect(() => {
    if (!enabled) return;

    const updateCacheInfo = () => {
      const cache = CacheManager.getInstance();
      const info: CacheInfo[] = [];
      
      // Get all cache keys from localStorage
      if (typeof window !== 'undefined') {
        const keys = Object.keys(localStorage);
        keys.forEach(key => {
          if (key.startsWith('cache_')) {
            const cacheKey = key.replace('cache_', '');
            const itemInfo = cache.getInfo(cacheKey);
            if (itemInfo) {
              info.push(itemInfo);
            }
          }
        });
      }

      setCacheInfo(info.sort((a, b) => b.created.getTime() - a.created.getTime()));
    };

    updateCacheInfo();
    const interval = setInterval(updateCacheInfo, 5000); // Update every 5 seconds

    return () => clearInterval(interval);
  }, [enabled]);

  const clearCache = () => {
    const cache = CacheManager.getInstance();
    cache.clear();
    setCacheInfo([]);
  };

  const formatSize = (bytes: number) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  };

  const formatTime = (date: Date) => {
    return date.toLocaleTimeString();
  };

  if (!enabled) return null;

  return (
    <div className="fixed bottom-4 right-4 z-50">
      <button
        onClick={() => setIsVisible(!isVisible)}
        className="px-4 py-2 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 transition-colors"
      >
        Cache ({cacheInfo.length})
      </button>

      {isVisible && (
        <div className="absolute bottom-12 right-0 w-96 max-h-96 bg-white border border-gray-200 rounded-lg shadow-xl overflow-hidden">
          <div className="px-4 py-2 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
            <h3 className="font-semibold text-gray-900">Cache Information</h3>
            <button
              onClick={clearCache}
              className="px-2 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700"
            >
              Clear All
            </button>
          </div>

          <div className="max-h-80 overflow-y-auto">
            {cacheInfo.length === 0 ? (
              <div className="px-4 py-8 text-center text-gray-500">
                No cache entries found
              </div>
            ) : (
              <div className="divide-y divide-gray-200">
                {cacheInfo.map((item, index) => (
                  <div key={index} className="px-4 py-3">
                    <div className="flex justify-between items-start mb-1">
                      <span className="text-sm font-mono text-gray-900 truncate flex-1 mr-2">
                        {item.key}
                      </span>
                      <span className="text-xs text-gray-500 whitespace-nowrap">
                        {formatSize(item.size)}
                      </span>
                    </div>
                    
                    <div className="text-xs text-gray-500 space-y-1">
                      <div>Created: {formatTime(item.created)}</div>
                      <div>Expires: {formatTime(item.expires)}</div>
                      {item.isExpired && (
                        <div className="text-red-600 font-semibold">EXPIRED</div>
                      )}
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>
        </div>
      )}
    </div>
  );
};

export default CacheDebugger;