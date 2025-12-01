import { useState, useEffect } from 'react';
import { homepageApi, type HomepageConstants, type GalleryItem, type SiteConfig } from '../lib/homepageApi';

// Custom hook untuk homepage constants
export function useHomepageConstants() {
  const [constants, setConstants] = useState<HomepageConstants | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    async function fetchConstants() {
      try {
        setLoading(true);
        setError(null);
        const data = await homepageApi.getConstants();
        setConstants(data);
      } catch (err) {
        setError(err instanceof Error ? err.message : 'Failed to fetch constants');
        console.error('Failed to fetch homepage constants:', err);
        
        // Fallback ke konstanta lokal jika API gagal
        const { HOMEPAGE_CONSTANTS } = await import('../constants');
        setConstants(HOMEPAGE_CONSTANTS);
      } finally {
        setLoading(false);
      }
    }

    fetchConstants();
  }, []);

  return { constants, loading, error };
}

// Custom hook untuk gallery item by ID
export function useGalleryItem(id: number) {
  const [item, setItem] = useState<GalleryItem | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    async function fetchItem() {
      if (!id) {
        setLoading(false);
        return;
      }

      try {
        setLoading(true);
        setError(null);
        const data = await homepageApi.getGalleryItem(id);
        setItem(data);
      } catch (err) {
        setError(err instanceof Error ? err.message : 'Failed to fetch gallery item');
        console.error('Failed to fetch gallery item:', err);
      } finally {
        setLoading(false);
      }
    }

    fetchItem();
  }, [id]);

  return { item, loading, error };
}

// Custom hook untuk gallery items by category
export function useGalleryByCategory(category: string) {
  const [items, setItems] = useState<GalleryItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    async function fetchItems() {
      if (!category) {
        setLoading(false);
        return;
      }

      try {
        setLoading(true);
        setError(null);
        const data = await homepageApi.getGalleryByCategory(category);
        setItems(data);
      } catch (err) {
        setError(err instanceof Error ? err.message : 'Failed to fetch gallery items');
        console.error('Failed to fetch gallery items by category:', err);
      } finally {
        setLoading(false);
      }
    }

    fetchItems();
  }, [category]);

  return { items, loading, error };
}

// Custom hook untuk site config
export function useSiteConfig() {
  const [config, setConfig] = useState<SiteConfig | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    async function fetchConfig() {
      try {
        setLoading(true);
        setError(null);
        const data = await homepageApi.getSiteConfig();
        setConfig(data);
      } catch (err) {
        setError(err instanceof Error ? err.message : 'Failed to fetch site config');
        console.error('Failed to fetch site config:', err);
      } finally {
        setLoading(false);
      }
    }

    fetchConfig();
  }, []);

  return { config, loading, error };
}