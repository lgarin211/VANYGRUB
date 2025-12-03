'use client';

import React, { useState, useEffect } from 'react';
import { RateLimitMonitor } from '../lib/rateLimitConfig';

interface RateLimitDebugProps {
  enabled?: boolean;
  position?: 'top-right' | 'top-left' | 'bottom-right' | 'bottom-left';
  showDetailed?: boolean;
}

export const RateLimitDebug: React.FC<RateLimitDebugProps> = ({ 
  enabled = process.env.NODE_ENV === 'development',
  position = 'bottom-right',
  showDetailed = false
}) => {
  const [stats, setStats] = useState(RateLimitMonitor.getStats());
  const [isExpanded, setIsExpanded] = useState(false);

  useEffect(() => {
    if (!enabled) return;

    const interval = setInterval(() => {
      setStats(RateLimitMonitor.getStats());
    }, 2000);

    return () => clearInterval(interval);
  }, [enabled]);

  if (!enabled) return null;

  const positionClasses = {
    'top-right': 'top-4 right-4',
    'top-left': 'top-4 left-4',
    'bottom-right': 'bottom-4 right-4',
    'bottom-left': 'bottom-4 left-4'
  };

  const getStatusColor = (rate: string) => {
    const numericRate = parseFloat(rate.replace('%', ''));
    if (numericRate < 5) return 'text-green-500';
    if (numericRate < 15) return 'text-yellow-500';
    return 'text-red-500';
  };

  const resetStats = () => {
    RateLimitMonitor.reset();
    setStats(RateLimitMonitor.getStats());
  };

  return (
    <div className={`fixed ${positionClasses[position]} z-50 bg-black bg-opacity-80 text-white text-xs p-3 rounded-lg shadow-lg max-w-xs`}>
      <div 
        className="cursor-pointer flex items-center justify-between"
        onClick={() => setIsExpanded(!isExpanded)}
      >
        <span className="font-bold">API Rate Monitor</span>
        <span className={`ml-2 ${getStatusColor(stats.rateLimitRate)}`}>
          {stats.rateLimitRate}
        </span>
      </div>
      
      {isExpanded && (
        <div className="mt-2 space-y-1">
          <div className="flex justify-between">
            <span>Total Requests:</span>
            <span className="font-mono">{stats.totalRequests}</span>
          </div>
          <div className="flex justify-between">
            <span>Cache Hit Rate:</span>
            <span className={`font-mono ${getStatusColor(stats.cacheHitRate)}`}>
              {stats.cacheHitRate}
            </span>
          </div>
          <div className="flex justify-between">
            <span>Rate Limit Hits:</span>
            <span className={`font-mono ${getStatusColor(stats.rateLimitRate)}`}>
              {stats.rateLimitHits} ({stats.rateLimitRate})
            </span>
          </div>
          <div className="flex justify-between">
            <span>Failures:</span>
            <span className={`font-mono ${getStatusColor(stats.failureRate)}`}>
              {stats.failures} ({stats.failureRate})
            </span>
          </div>
          <div className="flex justify-between">
            <span>Retries:</span>
            <span className="font-mono text-blue-400">{stats.retries}</span>
          </div>
          
          {showDetailed && (
            <>
              <hr className="border-gray-600 my-2" />
              <div className="text-xs text-gray-400">
                <div>Cache Hits: {stats.cacheHits}</div>
                <div>Rate Limited: {stats.rateLimitHits}</div>
                <div>Failed Requests: {stats.failures}</div>
              </div>
            </>
          )}
          
          <button
            onClick={resetStats}
            className="mt-2 w-full bg-gray-700 hover:bg-gray-600 text-white px-2 py-1 rounded text-xs"
          >
            Reset Stats
          </button>
        </div>
      )}
    </div>
  );
};

// Hook for accessing rate limiting stats in components
export const useRateLimitStats = () => {
  const [stats, setStats] = useState(RateLimitMonitor.getStats());

  useEffect(() => {
    const interval = setInterval(() => {
      setStats(RateLimitMonitor.getStats());
    }, 1000);

    return () => clearInterval(interval);
  }, []);

  return stats;
};