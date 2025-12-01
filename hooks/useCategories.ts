'use client';

import { useState, useEffect } from 'react';

// API client for categories
const API_BASE_URL = 'https://vanyadmin.progesio.my.id/api/vny';

export interface Category {
  id: number;
  name: string;
  slug: string;
  description: string;
  image: string;
  isActive: boolean;
  sortOrder: number;
}

class CategoryService {
  private baseUrl: string;

  constructor(baseUrl: string = API_BASE_URL) {
    this.baseUrl = baseUrl;
  }

  async getCategories(): Promise<Category[]> {
    try {
      const response = await fetch(`${this.baseUrl}/data`, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        cache: 'no-store',
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const data = await response.json();
      // Extract categories from the API response structure
      return data.categories || [];
    } catch (error) {
      console.error('Error fetching categories:', error);
      throw error;
    }
  }
}

const categoryService = new CategoryService();

// Hook for fetching categories
export const useCategories = () => {
  const [categories, setCategories] = useState<Category[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchCategories = async () => {
      try {
        setLoading(true);
        setError(null);
        
        const data = await categoryService.getCategories();
        setCategories(data);
      } catch (err) {
        console.error('Failed to fetch categories:', err);
        setError(err instanceof Error ? err.message : 'Failed to fetch categories');
        
        // Fallback to sample categories data
        const fallbackCategories: Category[] = [
          {
            id: 4,
            name: "Low Top",
            slug: "low-top",
            description: "low-top",
            image: "https://vanyadmin.progesio.my.id/storage/01KB8ZA9Q770T47WFR1P9Q1PF8.png",
            isActive: true,
            sortOrder: 0
          },
          {
            id: 3,
            name: "Sneakers",
            slug: "sneakers",
            description: "sneakers",
            image: "https://vanyadmin.progesio.my.id/storage/01KB8Z9B9D06RQAJQAZQQW047D.png",
            isActive: true,
            sortOrder: 1
          }
        ];
        setCategories(fallbackCategories);
      } finally {
        setLoading(false);
      }
    };

    fetchCategories();
  }, []);

  return { categories, loading, error };
};