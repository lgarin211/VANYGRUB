// Homepage API service
const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api/vny';

export interface GalleryItem {
  id: number;
  title: string;
  image: string;
  description: string;
  target: string;
  category: string;
}

export interface HomepageConstants {
  META: {
    TITLE: string;
    DESCRIPTION: string;
    KEYWORDS: string[];
  };
  HERO_SECTION: {
    TITLE: string;
    SUBTITLE: string;
    DESCRIPTION: string;
  };
  GALLERY_ITEMS: GalleryItem[];
  CATEGORIES: string[];
  COLORS: {
    PRIMARY: string;
    SECONDARY: string;
    ACCENT: string;
    GRADIENT: string;
  };
  ANIMATION: {
    CAROUSEL_INTERVAL: number;
    TRANSITION_DURATION: number;
  };
}

export interface SiteConfig {
  site_name: string;
  tagline: string;
  description: string;
  contact: {
    email: string;
    phone: string;
    address: string;
  };
  social_media: {
    facebook: string;
    instagram: string;
    twitter: string;
    youtube: string;
  };
  navigation: {
    main_menu: Array<{
      label: string;
      url: string;
      active: boolean;
    }>;
    footer_links: {
      services: Array<{
        label: string;
        url: string;
      }>;
      company: Array<{
        label: string;
        url: string;
      }>;
    };
  };
}

class HomepageApiService {
  private async fetchApi<T>(endpoint: string): Promise<T> {
    try {
      const response = await fetch(`${API_BASE_URL}/homepage${endpoint}`);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      
      if (data.status !== 'success') {
        throw new Error(data.message || 'API request failed');
      }
      
      return data.data;
    } catch (error) {
      console.error(`API Error for ${endpoint}:`, error);
      throw error;
    }
  }

  async getConstants(): Promise<HomepageConstants> {
    return this.fetchApi<HomepageConstants>('/constants');
  }

  async getGalleryItem(id: number): Promise<GalleryItem> {
    return this.fetchApi<GalleryItem>(`/gallery/${id}`);
  }

  async getGalleryByCategory(category: string): Promise<GalleryItem[]> {
    const response = await this.fetchApi<{ data: GalleryItem[]; count: number }>(`/category/${encodeURIComponent(category)}`);
    return response.data || [];
  }

  async getSiteConfig(): Promise<SiteConfig> {
    return this.fetchApi<SiteConfig>('/site-config');
  }
}

export const homepageApi = new HomepageApiService();