// Admin API client untuk VanyGrub
const API_BASE_URL = 'https://vanyadmin.progesio.my.id/api/vny';

class AdminAPI {
  constructor() {
    this.baseURL = API_BASE_URL;
    this.token = null;
  }

  // Set token untuk authentication
  setToken(token) {
    this.token = token;
    if (typeof window !== 'undefined') {
      localStorage.setItem('admin_token', token);
    }
  }

  // Get token dari localStorage
  getToken() {
    if (typeof window !== 'undefined') {
      return localStorage.getItem('admin_token');
    }
    return null;
  }

  // Remove token
  removeToken() {
    this.token = null;
    if (typeof window !== 'undefined') {
      localStorage.removeItem('admin_token');
    }
  }

  // Helper untuk membuat request
  async request(endpoint, options = {}) {
    const url = `${this.baseURL}${endpoint}`;
    const token = this.token || this.getToken();
    
    const config = {
      headers: {
        'Content-Type': 'application/json',
        ...(token && { 'Authorization': `Bearer ${token}` })
      },
      ...options
    };

    if (config.body && typeof config.body === 'object') {
      config.body = JSON.stringify(config.body);
    }

    try {
      const response = await fetch(url, config);
      const data = await response.json();
      
      if (!response.ok) {
        throw new Error(data.message || `HTTP ${response.status}`);
      }
      
      return data;
    } catch (error) {
      console.error('API Request failed:', error);
      throw error;
    }
  }

  // Authentication
  async login(email, password) {
    const data = await this.request('/auth/login', {
      method: 'POST',
      body: { email, password }
    });
    
    if (data.token) {
      this.setToken(data.token);
    }
    
    return data;
  }

  async logout() {
    try {
      await this.request('/auth/logout', {
        method: 'POST'
      });
    } finally {
      this.removeToken();
    }
  }

  async getProfile() {
    return this.request('/auth/profile');
  }

  // Categories
  async getCategories() {
    return this.request('/categories');
  }

  async getCategoryById(id) {
    return this.request(`/categories/${id}`);
  }

  async createCategory(categoryData) {
    return this.request('/categories', {
      method: 'POST',
      body: categoryData
    });
  }

  async updateCategory(id, categoryData) {
    return this.request(`/categories/${id}`, {
      method: 'PUT',
      body: categoryData
    });
  }

  async deleteCategory(id) {
    return this.request(`/categories/${id}`, {
      method: 'DELETE'
    });
  }

  // Products
  async getProducts() {
    return this.request('/products');
  }

  async getProductById(id) {
    return this.request(`/products/${id}`);
  }

  async createProduct(productData) {
    return this.request('/products', {
      method: 'POST',
      body: productData
    });
  }

  async updateProduct(id, productData) {
    return this.request(`/products/${id}`, {
      method: 'PUT',
      body: productData
    });
  }

  async deleteProduct(id) {
    return this.request(`/products/${id}`, {
      method: 'DELETE'
    });
  }

  // Orders
  async getOrders() {
    return this.request('/orders');
  }

  async getOrderById(id) {
    return this.request(`/orders/${id}`);
  }

  async updateOrderStatus(id, status) {
    return this.request(`/orders/${id}/status`, {
      method: 'PUT',
      body: { status }
    });
  }

  // Promo Codes
  async getPromoCodes() {
    return this.request('/promo-codes');
  }

  async getPromoCodeById(id) {
    return this.request(`/promo-codes/${id}`);
  }

  async createPromoCode(promoData) {
    return this.request('/promo-codes', {
      method: 'POST',
      body: promoData
    });
  }

  async updatePromoCode(id, promoData) {
    return this.request(`/promo-codes/${id}`, {
      method: 'PUT',
      body: promoData
    });
  }

  async deletePromoCode(id) {
    return this.request(`/promo-codes/${id}`, {
      method: 'DELETE'
    });
  }

  // Dashboard stats
  async getDashboardStats() {
    return this.request('/dashboard/stats');
  }
}

// Export singleton instance
const adminApi = new AdminAPI();
export default adminApi;