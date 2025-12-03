// Custom hooks for API data fetching with rate limiting protection
'use client';

import { useState, useEffect, useCallback } from 'react';
import { apiClient, transformApiData, withErrorHandling } from '../lib/api';
import { withCache } from '../lib/cache';
import { safeRequest, useRateLimitAware } from '../lib/requestManager';
import { getAdjustedConfig, RateLimitMonitor } from '../lib/rateLimitConfig';

// Hook for fetching all data (replacement for dataHome.json) - Rate Limited
export const useHomeData = () => {
  const fetchHomeData = useCallback(async () => {
    const response: any = await withErrorHandling(() => apiClient.getAllData()) as Promise<any>;
    
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
      
      return transformedData;
    } else {
      // Fallback to local data if API fails
      const homeData = await import('../constants/dataHome.json');
      return homeData.default;
    }
  }, []); // Empty dependency array since this function doesn't depend on any props or state

  return useRateLimitAware(
    'home-data',
    fetchHomeData,
    [],
    getAdjustedConfig('HOME_DATA')
  );
};

// Hook for fetching products - Rate Limited
export const useProducts = (params?: {
  category_id?: number;
  featured?: boolean;
  search?: string;
}) => {
  const fetchProducts = useCallback(async () => {
    const response: any = await withErrorHandling(() => apiClient.getProducts(params)) as Promise<any>;
    
    if (response && response.data) {
      return response.data.map(transformApiData.product);
    } else {
      // No fallback to local data - API should be the source of truth
      throw new Error('No products found');
    }
  }, [params?.category_id, params?.featured, params?.search]);

  return useRateLimitAware(
    'products',
    fetchProducts,
    [params?.category_id, params?.featured, params?.search],
    getAdjustedConfig('PRODUCTS')
  );
};

// Hook for fetching single product - Rate Limited
export const useProduct = (id: number) => {
  const fetchProduct = useCallback(async () => {
    if (!id) throw new Error('Product ID is required');
    
    const response: any = await withErrorHandling(() => apiClient.getProduct(id)) as Promise<any>;
    
    if (response && response.data) {
      return transformApiData.product(response.data);
    } else {
      throw new Error('Product not found');
    }
  }, [id]);

  return useRateLimitAware(
    `product-${id}`,
    fetchProduct,
    [id],
    getAdjustedConfig('PRODUCT_DETAIL')
  );
};

// Hook for fetching featured products - Rate Limited
export const useFeaturedProducts = () => {
  const fetchFeaturedProducts = async () => {
    try {
      const response: any = await withErrorHandling(() => apiClient.getFeaturedProducts());
      
      if (response && response.data) {
        return response.data.map(transformApiData.product);
      } else {
        // Fallback to local data
        const productsData = await import('../constants/productsData.json');
        return productsData.default.products.filter((p: any) => p.featured);
      }
    } catch (err) {
      console.error('Featured products fetch error:', err);
      // Fallback to local data
      const productsData = await import('../constants/productsData.json');
      return productsData.default.products.filter((p: any) => p.featured);
    }
  };

  return useRateLimitAware(
    'featured-products',
    fetchFeaturedProducts,
    [],
    getAdjustedConfig('FEATURED_PRODUCTS')
  );
};

// Hook for fetching categories - Rate Limited
export const useCategories = () => {
  const fetchCategories = useCallback(async () => {
    try {
      const response: any = await withErrorHandling(() => apiClient.getCategories());
      
      if (response && response.data) {
        return response.data.map(transformApiData.category);
      } else {
        // Fallback to local data
        const productsData = await import('../constants/productsData.json');
        return productsData.default.categories || [];
      }
    } catch (err) {
      console.error('Categories fetch error:', err);
      // Fallback to local data
      const productsData = await import('../constants/productsData.json');
      return productsData.default.categories || [];
    }
  }, []);

  return useRateLimitAware(
    'categories',
    fetchCategories,
    [],
    getAdjustedConfig('CATEGORIES')
  );
};

// Hook for fetching site config - Rate Limited
export const useSiteConfig = () => {
  const fetchSiteConfig = async () => {
    try {
      const response: any = await withErrorHandling(() => apiClient.getSiteConfig()) as Promise<any>;
      
      if (response && response.data) {
        return response.data;
      } else {
        // Fallback config
        return {
          contact: {
            phone: '+62 821-1142-4592' // Default fallback number
          }
        };
      }
    } catch (err) {
      console.error('Site config fetch error:', err);
      // Fallback config
      return {
        contact: {
          phone: '+62 821-1142-4592' // Default fallback number
        }
      };
    }
  };

  return useRateLimitAware(
    'site-config',
    fetchSiteConfig,
    [],
    getAdjustedConfig('SITE_CONFIG')
  );
};

// Cart and Checkout hooks
export const useCart = (sessionId?: string) => {
  const [cart, setCart] = useState<any>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const fetchCart = async () => {
    try {
      setLoading(true);
      const response = await safeRequest(
        `cart-${sessionId}`,
        () => withErrorHandling(() => apiClient.getCart(sessionId)),
        getAdjustedConfig('CART')
      );
      
      if (response) {
        setCart((response as any).data || response);
      }
    } catch (err) {
      setError('Failed to fetch cart');
      console.error('Cart fetch error:', err);
    } finally {
      setLoading(false);
    }
  };

  const addToCart = async (data: {
    product_id: number;
    quantity: number;
    size?: string;
    color?: string;
  }) => {
    try {
      const response = await safeRequest(
        `add-to-cart-${data.product_id}-${sessionId}`,
        () => withErrorHandling(() => apiClient.addToCart({ ...data, session_id: sessionId })),
        { ...getAdjustedConfig('CART'), cache: false }
      );
      
      if (response) {
        await fetchCart(); // Refresh cart
        return response;
      }
    } catch (err) {
      console.error('Add to cart error:', err);
      throw err;
    }
  };

  const updateCartItem = async (id: number, quantity: number) => {
    try {
      const response = await safeRequest(
        `update-cart-${id}-${quantity}`,
        () => withErrorHandling(() => apiClient.updateCartItem(id, { quantity })),
        { ...getAdjustedConfig('CART'), cache: false, debounce: 500 }
      );
      
      if (response) {
        await fetchCart(); // Refresh cart
        return response;
      }
    } catch (err) {
      console.error('Update cart error:', err);
      throw err;
    }
  };

  const removeFromCart = async (id: number) => {
    try {
      const response = await withErrorHandling(() => apiClient.removeFromCart(id));
      if (response) {
        await fetchCart(); // Refresh cart
        return response;
      }
    } catch (err) {
      console.error('Remove from cart error:', err);
      throw err;
    }
  };

  const clearCart = async () => {
    try {
      const response = await withErrorHandling(() => apiClient.clearCart(sessionId));
      if (response) {
        await fetchCart(); // Refresh cart
        return response;
      }
    } catch (err) {
      console.error('Clear cart error:', err);
      throw err;
    }
  };

  useEffect(() => {
    fetchCart();
  }, [sessionId]);

  return { 
    cart, 
    loading, 
    error, 
    addToCart, 
    updateCartItem, 
    removeFromCart, 
    clearCart,
    refreshCart: fetchCart
  };
};

// Hook for fetching orders
export const useOrders = () => {
  const [orders, setOrders] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const fetchOrders = async () => {
    try {
      setLoading(true);
      setError(null);
      
      const response = await safeRequest(
        'orders-list',
        () => withErrorHandling(() => apiClient.getOrders()),
        getAdjustedConfig('ORDERS')
      );
      
      setOrders((response as any[]) || []);
    } catch (err) {
      setError('Failed to fetch orders');
      console.error('Orders fetch error:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchOrders();
  }, []);

  return { orders, loading, error, refreshOrders: fetchOrders };
};

// Hook for creating orders
export const useCreateOrder = () => {
  const [creating, setCreating] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const createOrder = async (orderData: {
    customer_name: string;
    customer_email: string;
    customer_phone: string;
    shipping_address: string;
    shipping_city: string;
    shipping_postal_code: string;
    total_amount: number;
    notes?: string;
    session_id: string;
    items: Array<{
      product_id: number;
      quantity: number;
      price: number;
    }>;
  }) => {
    try {
      setCreating(true);
      setError(null);

      const orderRequest = async () => {
        const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api/vny';
        
        const response = await fetch(`${API_BASE_URL}/orders`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(orderData)
        });

        const result = await response.json();
        
        if (!response.ok) {
          throw new Error(result.message || 'Gagal membuat pesanan');
        }

        return {
          success: true,
          data: result.data,
          order_code: result.data?.order_number || result.data?.order_code
        };
      };

      return await safeRequest(
        `create-order-${orderData.session_id}-${Date.now()}`,
        orderRequest,
        { ...getAdjustedConfig('ORDER_CREATE'), cache: false }
      );

    } catch (err: any) {
      setError(err.message);
      throw err;
    } finally {
      setCreating(false);
    }
  };

  return { createOrder, creating, error };
};

// Hook for checkout process
export const useCheckout = () => {
  const { createOrder, creating, error } = useCreateOrder();
  
  const generateUniqueCode = () => {
    const timestamp = Date.now().toString().slice(-8);
    const random = Math.random().toString(36).substring(2, 6).toUpperCase();
    return `VNY${timestamp}${random}`;
  };

  const getWhatsAppNumber = (siteConfig?: any): string => {
    // Extract phone number from site config and format for WhatsApp
    const phone = siteConfig?.contact?.phone || '+62 821-1142-4592'; // Default fallback
    
    // Remove all non-numeric characters and format for WhatsApp
    const cleanPhone = phone.replace(/\D/g, '');
    
    // Ensure it starts with 62 (Indonesia country code)
    if (cleanPhone.startsWith('0')) {
      return '62' + cleanPhone.substring(1);
    } else if (cleanPhone.startsWith('62')) {
      return cleanPhone;
    } else {
      return '62' + cleanPhone;
    }
  };

  const generateWhatsAppMessage = (orderCode: string, customerInfo: any, cartItems: any[], total: number) => {
    const baseUrl = typeof window !== 'undefined' ? window.location.origin : '';
    const trackingUrl = `${baseUrl}/checkout/${orderCode}`;
    
    let message = `🛒 *PESANAN BARU VNY STORE*\n\n`;
    message += `📋 *Kode Pesanan:* ${orderCode}\n\n`;
    message += `🔗 *LINK CEK PESANAN (KLIK DI SINI):*\n`;
    message += `${trackingUrl}\n\n`;
    message += `👤 *Data Pembeli:*\n`;
    message += `Nama: ${customerInfo.name}\n`;
    message += `Phone: ${customerInfo.phone}\n`;
    message += `Email: ${customerInfo.email}\n`;
    message += `Alamat: ${customerInfo.address}\n`;
    message += `Kota: ${customerInfo.city}\n`;
    message += `Kode Pos: ${customerInfo.postalCode}\n`;
    if (customerInfo.notes) {
      message += `Catatan: ${customerInfo.notes}\n`;
    }
    message += `\n📦 *Detail Pesanan:*\n`;
    
    cartItems.forEach((item: any, index: number) => {
      message += `${index + 1}. ${item.name}\n`;
      message += `   Warna: ${item.color}\n`;
      message += `   Ukuran: ${item.size}\n`;
      message += `   Qty: ${item.quantity}x\n`;
      message += `   Harga: ${item.price}\n\n`;
    });
    
    message += `💰 *Total: Rp ${total.toLocaleString('id-ID')}*\n\n`;
    message += `Terima kasih telah berbelanja di VNY Store! 🙏`;
    
    return encodeURIComponent(message);
  };

  const processCheckout = async (customerInfo: any, cartItems: any[], pricingInfo: any, sessionId: string, siteConfig?: any) => {
    try {
      const orderPayload = {
        customer_name: customerInfo.name,
        customer_email: customerInfo.email,
        customer_phone: customerInfo.phone,
        shipping_address: customerInfo.address,
        shipping_city: customerInfo.city,
        shipping_postal_code: customerInfo.postalCode,
        total_amount: pricingInfo.total,
        notes: customerInfo.notes,
        session_id: sessionId,
        items: cartItems.map(item => ({
          product_id: item.product_id || item.id, // Use product_id if available, fallback to id for compatibility
          quantity: item.quantity,
          price: item.originalPrice || parseInt(item.price.replace(/\D/g, ''))
        }))
      };

      const result = await createOrder(orderPayload);
      const orderCode = result?.data?.order_code || result?.order_code || generateUniqueCode();
      
      // Store order data in localStorage for receipt page with server data structure
      const orderData = {
        orderCode,
        customerInfo,
        items: cartItems,
        pricing: pricingInfo,
        orderDate: new Date().toISOString(),
        status: (result as any)?.data?.status || (result as any)?.status || 'pending',
        // Store server ID for future reference
        serverId: (result as any)?.data?.id || (result as any)?.id
      };
      
      localStorage.setItem(`order_${orderCode}`, JSON.stringify(orderData));
      
      // Also store with server format for consistency
      if ((result as any)?.data || (result as any)?.order_code) {
        const serverData = {
          ...orderData,
          id: (result as any)?.data?.id || (result as any)?.id,
          customer_name: customerInfo.name,
          customer_email: customerInfo.email,
          customer_phone: customerInfo.phone,
          shipping_address: customerInfo.address,
          shipping_city: customerInfo.city,
          shipping_postal_code: customerInfo.postalCode,
          total_amount: pricingInfo.total,
          notes: customerInfo.notes,
          created_at: new Date().toISOString()
        };
        localStorage.setItem(`server_order_${orderCode}`, JSON.stringify(serverData));
      }

      // Generate WhatsApp message
      const whatsappNumber = getWhatsAppNumber(siteConfig);
      const message = generateWhatsAppMessage(orderCode, customerInfo, cartItems, pricingInfo.total);
      const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${message}`;
      
      return {
        success: true,
        orderCode,
        whatsappUrl,
        orderData: result.data
      };

    } catch (err: any) {
      // Fallback to offline mode
      const orderCode = generateUniqueCode();
      
      const orderData = {
        orderCode,
        customerInfo,
        items: cartItems,
        pricing: pricingInfo,
        orderDate: new Date().toISOString(),
        status: 'pending'
      };
      
      localStorage.setItem(`order_${orderCode}`, JSON.stringify(orderData));

      const whatsappNumber = getWhatsAppNumber(siteConfig);
      const message = generateWhatsAppMessage(orderCode, customerInfo, cartItems, pricingInfo.total);
      const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${message}`;
      
      return {
        success: false,
        orderCode,
        whatsappUrl,
        error: err.message,
        fallback: true
      };
    }
  };

  return { 
    processCheckout, 
    creating, 
    error,
    generateWhatsAppMessage,
    generateUniqueCode,
    getWhatsAppNumber
  };
};

// Hook for order tracking
export const useOrderTracking = (orderCode: string) => {
  const [orderData, setOrderData] = useState<any>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const fetchOrderData = async () => {
    if (!orderCode) return;
    
    try {
      setLoading(true);
      setError(null);
      
      // Try to fetch from API first with rate limiting
      const response = await safeRequest(
        `order-tracking-${orderCode}`,
        () => withErrorHandling(() => apiClient.getOrderByCode(orderCode)),
        getAdjustedConfig('ORDER_TRACKING')
      ) as any;
      
      // Handle different response formats from API
      let apiOrderData = null;
      if (response) {
        // Check different possible response structures
        if (response.data && Array.isArray(response.data) && response.data.length > 0) {
          // Find the exact order by order_code
          apiOrderData = response.data.find((order: any) => order.order_code === orderCode) || response.data[0];
        } else if (response.data && !Array.isArray(response.data)) {
          apiOrderData = response.data;
        } else if (response.order) {
          apiOrderData = response.order;
        } else if (Array.isArray(response) && response.length > 0) {
          apiOrderData = response.find((order: any) => order.order_code === orderCode) || response[0];
        } else if (response.id || response.order_code) {
          apiOrderData = response;
        }
      }
      
      if (apiOrderData) {
        // Transform API data to match frontend format
        const subtotal = parseFloat(apiOrderData.subtotal || apiOrderData.total_amount || '0');
        const discountAmount = parseFloat(apiOrderData.discount_amount || '0');
        const totalAmount = parseFloat(apiOrderData.total_amount || '0');
        
        const transformedData = {
          orderCode: apiOrderData.order_code || apiOrderData.orderCode || orderCode,
          customerInfo: {
            name: apiOrderData.customer_name || 'Customer',
            phone: apiOrderData.customer_phone || '',
            email: apiOrderData.customer_email || '',
            address: apiOrderData.shipping_address || '',
            city: apiOrderData.shipping_city || '',
            postalCode: apiOrderData.shipping_postal_code || '',
            notes: apiOrderData.notes || ''
          },
          items: apiOrderData.items ? (typeof apiOrderData.items === 'string' ? JSON.parse(apiOrderData.items || '[]') : apiOrderData.items) : [],
          pricing: {
            subtotal: subtotal,
            itemDiscount: discountAmount,
            promoDiscount: 0,
            appliedPromo: null,
            tax: Math.round(totalAmount * 0.1),
            shipping: 0,
            total: totalAmount
          },
          orderDate: apiOrderData.created_at || apiOrderData.orderDate || new Date().toISOString(),
          status: apiOrderData.status || 'pending'
        };
        setOrderData(transformedData);
      } else {
        // Fallback to localStorage
        const storedData = localStorage.getItem(`order_${orderCode}`);
        if (storedData) {
          const data = JSON.parse(storedData);
          setOrderData(data);
        } else {
          setError('Order not found');
        }
      }
    } catch (err) {
      console.error('Error fetching order data:', err);
      // Fallback to localStorage on API error
      try {
        const storedData = localStorage.getItem(`order_${orderCode}`);
        if (storedData) {
          const data = JSON.parse(storedData);
          setOrderData(data);
        } else {
          setError('Order not found');
        }
      } catch (fallbackErr) {
        setError('Order not found');
      }
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchOrderData();
  }, [orderCode]);

  return { orderData, loading, error, refetch: fetchOrderData };
};

// Hook for transactions page
export const useTransactions = (sessionId?: string) => {
  const [transactions, setTransactions] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const fetchTransactions = async () => {
    try {
      setLoading(true);
      setError(null);
      
      // Try to fetch from API first with rate limiting
      const response = await safeRequest(
        `transactions-${sessionId || 'all'}`,
        () => withErrorHandling(() => apiClient.getOrders(sessionId)),
        getAdjustedConfig('TRANSACTIONS')
      ) as any;
      
      if (response && response.data) {
        // Transform API data to match frontend transaction format
        const transformedTransactions = response.data.map((order: any) => ({
          id: order.order_code || order.id,
          date: order.created_at ? new Date(order.created_at).toISOString().split('T')[0] : new Date().toISOString().split('T')[0],
          status: order.status || 'pending',
          items: order.items ? (typeof order.items === 'string' ? JSON.parse(order.items) : order.items) : [],
          total: parseFloat(order.total_amount || '0'),
          shippingAddress: order.shipping_address || '',
          paymentMethod: order.payment_method || 'Pending',
          trackingNumber: order.tracking_number || undefined,
          customerInfo: {
            name: order.customer_name || '',
            phone: order.customer_phone || '',
            email: order.customer_email || '',
            address: order.shipping_address || ''
          }
        }));
        
        setTransactions(transformedTransactions);
      } else {
        // Fallback to demo data if API fails
        setTransactions([]);
      }
    } catch (err) {
      setError('Failed to fetch transactions');
      console.error('Transactions fetch error:', err);
      
      // Fallback to demo data
      setTransactions([]);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchTransactions();
  }, [sessionId]);

  const refreshTransactions = () => fetchTransactions();

  return { transactions, loading, error, refreshTransactions };
};