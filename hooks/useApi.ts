// Custom hooks for API data fetching
'use client';

import { useState, useEffect } from 'react';
import { apiClient, transformApiData, withErrorHandling } from '../lib/api';

// Hook for fetching all data (replacement for dataHome.json)
export const useHomeData = () => {
  const [data, setData] = useState<any>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true);
        const response: any = await withErrorHandling(() => apiClient.getAllData());
        
        if (response) {
          // Transform API data to match the expected format
          const transformedData = {
            heroSection: {
              slides: response.heroSections.map(transformApiData.heroSection),
              autoPlayInterval: 5000
            },
            ourCollection: {
              title: "Our Collection",
              subtitle: "Discover our premium products",
              products: response.products.slice(0, 8).map(transformApiData.product),
              carouselConfig: {
                itemsPerView: 4,
                gap: 20
              }
            },
            specialOffers: {
              title: "Special Offers",
              subtitle: "Don't miss out on these amazing deals",
              offers: response.products.filter((p: any) => p.salePrice).slice(0, 3).map(transformApiData.product),
              cardConfig: {
                showDiscount: true
              }
            },
            productGrid: response.productGrid || {
              items: [],
              sizeConfig: {
                itemsPerRow: {
                  desktop: 3,
                  tablet: 2,
                  mobile: 1
                },
                spacing: 'medium'
              }
            }
          };
          
          setData(transformedData);
        } else {
          // Fallback to local data if API fails
          const homeData = await import('../constants/dataHome.json');
          setData(homeData.default);
        }
      } catch (err) {
        setError('Failed to fetch home data');
        // Fallback to local data
        try {
          const homeData = await import('../constants/dataHome.json');
          setData(homeData.default);
        } catch (fallbackErr) {
          console.error('Failed to load fallback data:', fallbackErr);
        }
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  return { data, loading, error };
};

// Hook for fetching products
export const useProducts = (params?: {
  category_id?: number;
  featured?: boolean;
  search?: string;
}) => {
  const [products, setProducts] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchProducts = async () => {
      try {
        setLoading(true);
        const response: any = await withErrorHandling(() => apiClient.getProducts(params));
        
        if (response && response.data) {
          const transformedProducts = response.data.map(transformApiData.product);
          setProducts(transformedProducts);
        } else {
          // Fallback to local data
          const productsData = await import('../constants/productsData.json');
          setProducts(productsData.default.products);
        }
      } catch (err) {
        setError('Failed to fetch products');
        // Fallback to local data
        try {
          const productsData = await import('../constants/productsData.json');
          setProducts(productsData.default.products);
        } catch (fallbackErr) {
          console.error('Failed to load fallback products:', fallbackErr);
        }
      } finally {
        setLoading(false);
      }
    };

    fetchProducts();
  }, [params?.category_id, params?.featured, params?.search]);

  return { products, loading, error };
};

// Hook for fetching single product
export const useProduct = (id: number) => {
  const [product, setProduct] = useState<any>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchProduct = async () => {
      try {
        setLoading(true);
        const response: any = await withErrorHandling(() => apiClient.getProduct(id));
        
        if (response && response.data) {
          const transformedProduct = transformApiData.product(response.data);
          setProduct(transformedProduct);
        } else {
          // Fallback to local data
          const productsData = await import('../constants/productsData.json');
          const localProduct = productsData.default.products.find((p: any) => p.id === id);
          setProduct(localProduct || null);
        }
      } catch (err) {
        setError('Failed to fetch product');
        // Fallback to local data
        try {
          const productsData = await import('../constants/productsData.json');
          const localProduct = productsData.default.products.find((p: any) => p.id === id);
          setProduct(localProduct || null);
        } catch (fallbackErr) {
          console.error('Failed to load fallback product:', fallbackErr);
        }
      } finally {
        setLoading(false);
      }
    };

    if (id) {
      fetchProduct();
    }
  }, [id]);

  return { product, loading, error };
};

// Hook for fetching featured products
export const useFeaturedProducts = () => {
  const [products, setProducts] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchFeaturedProducts = async () => {
      try {
        setLoading(true);
        const response: any = await withErrorHandling(() => apiClient.getFeaturedProducts());
        
        if (response && response.data) {
          const transformedProducts = response.data.map(transformApiData.product);
          setProducts(transformedProducts);
        } else {
          // Fallback to local data
          const productsData = await import('../constants/productsData.json');
          const featuredProducts = productsData.default.products.filter((p: any) => p.featured);
          setProducts(featuredProducts);
        }
      } catch (err) {
        setError('Failed to fetch featured products');
        // Fallback to local data
        try {
          const productsData = await import('../constants/productsData.json');
          const featuredProducts = productsData.default.products.filter((p: any) => p.featured);
          setProducts(featuredProducts);
        } catch (fallbackErr) {
          console.error('Failed to load fallback featured products:', fallbackErr);
        }
      } finally {
        setLoading(false);
      }
    };

    fetchFeaturedProducts();
  }, []);

  return { products, loading, error };
};

// Hook for fetching categories
export const useCategories = () => {
  const [categories, setCategories] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchCategories = async () => {
      try {
        setLoading(true);
        const response: any = await withErrorHandling(() => apiClient.getCategories());
        
        if (response && response.data) {
          const transformedCategories = response.data.map(transformApiData.category);
          setCategories(transformedCategories);
        } else {
          // Fallback to local data
          const productsData = await import('../constants/productsData.json');
          setCategories(productsData.default.categories || []);
        }
      } catch (err) {
        setError('Failed to fetch categories');
        // Fallback to local data
        try {
          const productsData = await import('../constants/productsData.json');
          setCategories(productsData.default.categories || []);
        } catch (fallbackErr) {
          console.error('Failed to load fallback categories:', fallbackErr);
        }
      } finally {
        setLoading(false);
      }
    };

    fetchCategories();
  }, []);

  return { categories, loading, error };
};