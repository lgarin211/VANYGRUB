'use client';

import { ReactNode } from 'react';
import CacheDebugger from './CacheDebugger';
import { useCacheCleanup } from '../hooks/useCache';

interface CacheProviderProps {
  children: ReactNode;
}

const CacheProvider: React.FC<CacheProviderProps> = ({ children }) => {
  // Use cache cleanup hook with 5 minute interval
  useCacheCleanup(5 * 60 * 1000);

  return (
    <>
      {children}
      <CacheDebugger enabled={process.env.NODE_ENV === 'development'} />
    </>
  );
};

export default CacheProvider;