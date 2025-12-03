// API configuration and base URL
const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || (process.env.NODE_ENV === 'development' ? 'http://localhost:8000/api/vny' : 'https://vanyadmin.progesio.my.id/api/vny');

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

  // Site Configuration API
  async getSiteConfig() {
    return this.request('/homepage/site-config');
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

  // Cart API
  async getCart(sessionId?: string) {
    return this.request(`/cart${sessionId ? `?session_id=${sessionId}` : ''}`);
  }

  async addToCart(data: {
    product_id: number;
    quantity: number;
    size?: string;
    color?: string;
    session_id?: string;
  }) {
    return this.request('/cart', {
      method: 'POST',
      body: JSON.stringify(data),
    });
  }

  async updateCartItem(id: number, data: { quantity: number }) {
    return this.request(`/cart/${id}`, {
      method: 'PUT',
      body: JSON.stringify(data),
    });
  }

  async removeFromCart(id: number) {
    return this.request(`/cart/${id}`, {
      method: 'DELETE',
    });
  }

  async clearCart(sessionId?: string) {
    return this.request('/cart/clear', {
      method: 'POST',
      body: JSON.stringify({ session_id: sessionId }),
    });
  }

  // Order API
  async getOrders(sessionId?: string) {
    return this.request(`/orders${sessionId ? `?session_id=${sessionId}` : ''}`);
  }

  async createOrder(data: {
    customer_name: string;
    customer_email: string;
    customer_phone: string;
    shipping_address: string;
    shipping_city: string;
    shipping_postal_code: string;
    payment_method: string;
    notes?: string;
    session_id?: string;
  }) {
    return this.request('/orders', {
      method: 'POST',
      body: JSON.stringify(data),
    });
  }

  async getOrder(id: number) {
    return this.request(`/orders/${id}`);
  }

  async getOrderByCode(orderCode: string) {
    // List of possible endpoints to try (in order of preference)
    const endpoints = [
      `/orders?order_code=${orderCode}`, // This one works based on our test
      `/orders/track/${orderCode}`,
      `/orders/find/${orderCode}`,
      `/orders/code/${orderCode}`,
      `/orders/${orderCode}`,
      `/order-tracking/${orderCode}`
    ];
    
    let lastError: any = null;
    
    for (const endpoint of endpoints) {
      try {
        const result = await this.request(endpoint);
        return result;
      } catch (error) {
        lastError = error;
        continue;
      }
    }
    
    // If all endpoints fail, throw the last error
    throw lastError || new Error('Order not found');
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
    price: `Rp ${(apiProduct.salePrice || apiProduct.price)?.toLocaleString('id-ID') || '0'}`,
    originalPrice: apiProduct.price,
    salePrice: apiProduct.salePrice,
    image: apiProduct.image || apiProduct.mainImage || apiProduct.imageUrl,
    description: apiProduct.description,
    detailedDescription: apiProduct.detailedDescription,
    shortDescription: apiProduct.shortDescription,
    serial: apiProduct.serialNumber || 'N/A',
    inStock: apiProduct.inStock,
    featured: apiProduct.isFeatured,
    badge: apiProduct.badge || (apiProduct.isFeatured ? 'Featured' : null) || (apiProduct.salePrice ? 'Sale' : null),
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
    console.log('withErrorHandling: Starting API call');
    const result = await apiCall();
    console.log('withErrorHandling: Success, result:', result);
    return result;
  } catch (error) {
    console.error('withErrorHandling: API call failed:', error);
    console.log('withErrorHandling: Returning fallback data:', fallbackData);
    return fallbackData || null;
  }
};

export default apiClient;