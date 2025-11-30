// API configuration and base URL
const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || 'https://vanyadmin.progesio.my.id/api/vny';

// API client with error handling
class ApiClient {
  private baseURL: string;

  constructor(baseURL: string) {
    this.baseURL = baseURL;
  }

  private async request<T>(endpoint: string, options?: RequestInit): Promise<T> {
    const url = `${this.baseURL}${endpoint}`;
    
    try {
      const response = await fetch(url, {
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          ...options?.headers,
        },
        ...options,
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const data = await response.json();
      return data;
    } catch (error) {
      console.error(`API request failed for ${url}:`, error);
      throw error;
    }
  }

  // Get all data for Next.js constants format
  async getAllData() {
    return this.request('/data');
  }

  // Get home page data
  async getHomeData() {
    return this.request('/home-data');
  }

  // Categories API
  async getCategories() {
    return this.request('/categories');
  }

  async getCategory(id: number) {
    return this.request(`/categories/${id}`);
  }

  // Products API
  async getProducts(params?: {
    category_id?: number;
    featured?: boolean;
    search?: string;
  }) {
    const searchParams = new URLSearchParams();
    
    if (params?.category_id) {
      searchParams.append('category_id', params.category_id.toString());
    }
    if (params?.featured) {
      searchParams.append('featured', 'true');
    }
    if (params?.search) {
      searchParams.append('search', params.search);
    }

    const queryString = searchParams.toString();
    const endpoint = queryString ? `/products?${queryString}` : '/products';
    
    return this.request(endpoint);
  }

  async getProduct(id: number) {
    return this.request(`/products/${id}`);
  }

  async getProductBySlug(slug: string) {
    return this.request(`/products/slug/${slug}`);
  }

  async getFeaturedProducts() {
    return this.request('/featured-products');
  }

  // Hero Sections API
  async getHeroSections() {
    return this.request('/hero-sections');
  }

  async getHeroSection(id: number) {
    return this.request(`/hero-sections/${id}`);
  }
}

// Create API client instance
export const apiClient = new ApiClient(API_BASE_URL);

// Data transformation helpers
export const transformApiData = {
  // Transform product data from API to frontend format
  product: (apiProduct: any) => ({
    id: apiProduct.id,
    name: apiProduct.name,
    category: apiProduct.category?.name || '',
    price: `Rp ${apiProduct.salePrice || apiProduct.price}`,
    originalPrice: apiProduct.price,
    salePrice: apiProduct.salePrice,
    image: apiProduct.image || apiProduct.mainImage,
    description: apiProduct.description,
    detailedDescription: apiProduct.detailedDescription,
    shortDescription: apiProduct.shortDescription,
    serial: apiProduct.serialNumber || 'N/A',
    inStock: apiProduct.inStock,
    featured: apiProduct.isFeatured,
    gallery: apiProduct.gallery || [],
    colors: apiProduct.colors || [],
    sizes: apiProduct.sizes || [],
    weight: apiProduct.weight,
    dimensions: apiProduct.dimensions,
    countryOrigin: apiProduct.countryOrigin,
    warranty: apiProduct.warranty,
    releaseDate: apiProduct.releaseDate,
    sku: apiProduct.sku,
    stockQuantity: apiProduct.stockQuantity,
    slug: apiProduct.slug
  }),

  // Transform hero section data
  heroSection: (apiHero: any) => ({
    id: apiHero.id,
    title: apiHero.title,
    subtitle: apiHero.subtitle,
    description: apiHero.description,
    image: apiHero.image,
    bgColor: apiHero.bgColor,
    textColor: apiHero.textColor,
    buttonText: apiHero.buttonText,
    price: apiHero.price
  }),

  // Transform category data
  category: (apiCategory: any) => ({
    id: apiCategory.id,
    name: apiCategory.name,
    slug: apiCategory.slug,
    description: apiCategory.description,
    image: apiCategory.image,
    isActive: apiCategory.isActive,
    productsCount: apiCategory.productsCount
  })
};

// Error handling wrapper for API calls
export const withErrorHandling = async <T>(
  apiCall: () => Promise<T>,
  fallbackData?: T
): Promise<T | null> => {
  try {
    return await apiCall();
  } catch (error) {
    console.error('API call failed:', error);
    return fallbackData || null;
  }
};

export default apiClient;