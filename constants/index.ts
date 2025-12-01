// Constants barrel exports
export * from './homeConstants';
export * from './siteConfig';

// Re-export existing JSON data
export { default as dataHome } from './dataHome.json';
export { default as productsData } from './productsData.json';  
export { default as productsDataDetailed } from './productsDataDetailed.json';

// API fallback - ensure we always have data even if API fails
export const API_FALLBACK_CONSTANTS = HOMEPAGE_CONSTANTS;