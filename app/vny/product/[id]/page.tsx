'use client';

import React, { useState, useEffect, useCallback } from 'react';
import Link from 'next/link';
import SafeImage from '../../../../components/SafeImage';
import { showSuccess, showError, showCart } from '../../../../utils/sweetAlert';
import { getSessionId } from '../../../../utils/session';
import { useParams } from 'next/navigation';
import { useProduct, useCart, useOrders } from '../../../../hooks/useApi';
import Header from '../../../../components/Header';
import CartModal from '../../../../components/CartModal';
import TransactionModal from '../../../../components/TransactionModal';
import '../../../../styles/product-detail.css';

interface Product {
  id: number;
  name: string;
  category: string;
  price: string;
  originalPrice: number;
  discount?: string;
  gallery: string[];
  mainImage: string;
  description: string;
  detailDescription: string;
  serial: string;
  inStock: boolean;
  featured: boolean;
  colors: string[];
  sizes: number[];
  weight: string;
  countryOrigin: string;
  warranty: string;
  releaseDate: string;
}

interface CartItem {
  id: number;
  name: string;
  price: string;
  originalPrice: number;
  image: string;
  color: string;
  size: string | number;
  quantity: number;
}

interface TransactionItem {
  id: number;
  name: string;
  image: string;
  quantity: number;
  price: string;
  color: string;
  size: string | number;
}

interface Transaction {
  id: string;
  date: string;
  status: 'pending' | 'processing' | 'shipped' | 'delivered' | 'cancelled';
  items: TransactionItem[];
  total: number;
  shippingAddress: string;
  paymentMethod: string;
  trackingNumber?: string;
}

const ProductDetail: React.FC = () => {
  const params = useParams();
  const productId = Number(params.id);
  const [quantity, setQuantity] = useState(1);
  const [activeImageIndex, setActiveImageIndex] = useState(0);
  const [activeView, setActiveView] = useState<'images' | '3d'>('images');
  const [selectedSize, setSelectedSize] = useState<string | number | null>(null);
  const [selectedColor, setSelectedColor] = useState<string>('');
  const [isAddingToCart, setIsAddingToCart] = useState(false);
  
  // Modal states
  const [showCartModal, setShowCartModal] = useState(false);
  const [showTransactionModal, setShowTransactionModal] = useState(false);
  
  // Cart and transaction data from API
  const sessionId = getSessionId();
  const { cart, refreshCart, addToCart } = useCart(sessionId);
  const { orders } = useOrders();
  const cartItems = cart?.items || [];
  
  // Use orders from API
  const transactions = orders || [];

  // Load product from API
  const { data: product, loading, error } = useProduct(productId);

  useEffect(() => {
    if (product && product.colors && Array.isArray(product.colors) && product.colors.length > 0) {
      // Set default color based on product ID to match the displayed image
      const getDefaultColor = (productId: number) => {
        switch (productId) {
          case 1: return "Black/Red"; // Air Jordan 1 - matches typical red/black colorway
          case 2: return "Panda (Black/White)"; // Nike Dunk - matches black/white panda colorway
          case 3: return "Vintage Khaki/Brown"; // Converse Chuck 70 - matches the tan/brown shoe in image
          case 4: return "Black/White"; // Vans Old Skool - classic black/white
          default: return product.colors[0];
        }
      };
      
      const defaultColor = getDefaultColor(productId);
      const colorExists = product.colors.includes(defaultColor);
      setSelectedColor(colorExists ? defaultColor : product.colors[0]);
    }
  }, [product, productId]);

  // Image navigation handler
  const handleImageNavigation = useCallback((direction: 'prev' | 'next') => {
    if (!product || !product.gallery || product.gallery.length <= 1) return;
    
    if (direction === 'prev') {
      const newIndex = activeImageIndex === 0 ? product.gallery.length - 1 : activeImageIndex - 1;
      setActiveImageIndex(newIndex);
    } else {
      const newIndex = activeImageIndex === product.gallery.length - 1 ? 0 : activeImageIndex + 1;
      setActiveImageIndex(newIndex);
    }
    
    // Ensure we're in image view when navigating
    setActiveView('images');
  }, [product, activeImageIndex]);

  // Keyboard navigation
  useEffect(() => {
    const handleKeyDown = (event: KeyboardEvent) => {
      if (activeView === 'images' && product && product.gallery && product.gallery.length > 1) {
        if (event.key === 'ArrowLeft') {
          event.preventDefault();
          handleImageNavigation('prev');
        } else if (event.key === 'ArrowRight') {
          event.preventDefault();
          handleImageNavigation('next');
        }
      }
    };

    window.addEventListener('keydown', handleKeyDown);
    return () => window.removeEventListener('keydown', handleKeyDown);
  }, [activeView, activeImageIndex, product, handleImageNavigation]);

  // Cart handlers
  const handleAddToCart = async () => {
    if (!product || !selectedSize || !selectedColor || !product.inStock) {
      return;
    }

    setIsAddingToCart(true);
    try {
      await addToCart({
        product_id: product.id,
        quantity: quantity,
        size: selectedSize.toString(),
        color: selectedColor
      });

      // Refresh cart data after successful add
      await refreshCart();
      
      // Show success feedback
      const result = await showCart(
        'Berhasil Ditambahkan!',
        `${quantity} ${product.name} (${selectedColor}, Size ${selectedSize}) telah ditambahkan ke keranjang`
      );
      
      // Handle user choice
      if (result.isConfirmed) {
        window.location.href = '/vny/cart';
      }
      
      // Reset form
      setQuantity(1);
    } catch (error) {
      console.error('Error adding to cart:', error);
      showError('Gagal Menambahkan', 'Gagal menambahkan produk ke keranjang. Silakan coba lagi.');
    } finally {
      setIsAddingToCart(false);
    }
  };

  const { updateCartItem, removeFromCart } = useCart(sessionId);

  const handleUpdateCartQuantity = async (itemId: number, newQuantity: number) => {
    try {
      await updateCartItem(itemId, newQuantity);
      await refreshCart();
    } catch (error) {
      console.error('Error updating cart:', error);
      showError('Gagal Update', 'Gagal mengupdate item di keranjang.');
    }
  };

  const handleRemoveCartItem = async (itemId: number) => {
    try {
      await removeFromCart(itemId);
      await refreshCart();
    } catch (error) {
      console.error('Error removing from cart:', error);
      showError('Gagal Hapus', 'Gagal menghapus item dari keranjang.');
    }
  };

  // 3D Model URLs untuk berbagai produk
  const get3DModelUrl = (productId: number) => {
    const modelUrls = [
      "https://sketchfab.com/models/9425d7ed2fee4d7582c48d57f1eeb93a/embed?autostart=1&dnt=1",
      "https://sketchfab.com/models/90ea1a863ea547fe80da67db2ef835dc/embed",
      "https://sketchfab.com/models/3387ec269ff64f6a847752c88164f8bf/embed",
      "https://sketchfab.com/models/311cc9511f25496cbd7af392d14fd89f/embed",
      "https://sketchfab.com/models/7ce93245f9d8440180d25d1c6b566fa0/embed",
      "https://sketchfab.com/models/013b775a5ad5441ebadb316c98902de3/embed",
      "https://sketchfab.com/models/3f871b7a3a09445fb8f93c7a7e210616/embed"
    ];
    
    return modelUrls[productId % modelUrls.length] || modelUrls[0];
  };

  // Show loading state
  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-screen">
        <div className="text-center">
          <div className="inline-block w-12 h-12 mb-4 border-b-2 border-red-600 rounded-full animate-spin"></div>
          <p className="text-xl text-gray-600">Loading product...</p>
        </div>
      </div>
    );
  }

  // Show error or product not found
  if (error || !product) {
    return (
      <div className="flex items-center justify-center min-h-screen">
        <div className="text-center">
          <h1 className="mb-4 text-2xl font-bold text-gray-900">
            {error ? 'Error loading product' : 'Produk tidak ditemukan'}
          </h1>
          <p className="mb-4 text-gray-600">
            {error ? 'Please try again later' : 'The product you\'re looking for doesn\'t exist'}
          </p>
          <Link href="/vny/product" className="text-red-600 hover:text-red-800">
            ← Kembali ke Produk
          </Link>
        </div>
      </div>
    );
  }



  // Get current image to display
  const getCurrentImage = () => {
    if (!product || !product.gallery || product.gallery.length === 0) return '';
    return product.gallery[activeImageIndex] || product.gallery[0];
  };

  return (
    <div className="min-h-screen bg-gray-100">
      {/* Header */}
      <Header />
      
      {/* Breadcrumb */}
      <div className="py-4 bg-gray-50">
        <div className="container px-4 mx-auto">
          <div className="flex items-center space-x-2 text-sm">
            <Link href="/" className="text-red-600 hover:text-red-700">
              Vany Group
            </Link>
            <span className="text-gray-400">/</span>
            <Link href="/vny" className="text-red-600 hover:text-red-700">
              VNY Store
            </Link>
            <span className="text-gray-400">/</span>
            <Link href="/vny/product" className="text-red-600 hover:text-red-700">
              Product
            </Link>
            <span className="text-gray-400">/</span>
            <span className="font-medium text-gray-900">{product?.name || 'Product Detail'}</span>
          </div>
        </div>
      </div>

      <div className="container px-4 py-8 mx-auto">
        {/* Back to Product Link */}
        <div className="mb-6">
          <Link href="/vny/product" className="flex items-center text-red-600 hover:text-red-800">
            ← Kembali ke Produk
          </Link>
        </div>

        <div className="p-6 bg-white rounded-lg lg:p-8">
          <div className="grid grid-cols-1 gap-8 lg:grid-cols-2 lg:gap-12">
            {/* Product Images Section */}
            <div className="space-y-4">
              {/* Main Image/3D Viewer */}
              <div className="relative overflow-hidden shadow-lg aspect-square bg-gradient-to-br from-gray-100 via-gray-50 to-gray-100 rounded-2xl group">
                {activeView === '3d' ? (
                  // 3D Sketchfab Viewer
                  <iframe
                    title="3D Product Model"
                    frameBorder="0"
                    allowFullScreen
                    allow="autoplay; fullscreen; xr-spatial-tracking"
                    className="w-full h-full"
                    src={get3DModelUrl(productId)}
                  />
                ) : (
                  // Image Slider
                  <>
                    <SafeImage
                      src={getCurrentImage()}
                      alt={`${product.name} - ${selectedColor} - Image ${activeImageIndex + 1}`}
                      width={600}
                      height={600}
                      className="object-contain w-full h-full p-8 transition-all duration-300"
                      key={activeImageIndex} // Force re-render when image changes
                    />
                    
                    {/* Image Navigation Arrows */}
                    {product.gallery && product.gallery.length > 1 && (
                      <>
                        <button 
                          onClick={(e) => {
                            e.preventDefault();
                            handleImageNavigation('prev');
                          }}
                          className="absolute p-3 text-white transition-all transform -translate-y-1/2 rounded-full opacity-0 left-4 top-1/2 bg-black/60 hover:bg-black/80 group-hover:opacity-100 hover:scale-110 active:scale-95"
                          title="Previous Image (←)"
                        >
                          <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                          </svg>
                        </button>
                        <button 
                          onClick={(e) => {
                            e.preventDefault();
                            handleImageNavigation('next');
                          }}
                          className="absolute p-3 text-white transition-all transform -translate-y-1/2 rounded-full opacity-0 right-4 top-1/2 bg-black/60 hover:bg-black/80 group-hover:opacity-100 hover:scale-110 active:scale-95"
                          title="Next Image (→)"
                        >
                          <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                          </svg>
                        </button>
                      </>
                    )}
                    
                    {/* Image Counter & Color Info */}
                    <div className="absolute px-3 py-1 text-sm text-white rounded-full bottom-4 right-4 bg-black/50">
                      {activeImageIndex + 1} / {product.gallery?.length || 0}
                    </div>
                    <div className="absolute px-3 py-1 text-sm text-white rounded-full bottom-4 left-4 bg-black/50">
                      {selectedColor}
                    </div>
                  </>
                )}
              </div>
              
              {/* Thumbnail Images Grid */}
              <div className="flex flex-wrap justify-start gap-2">
                {/* Image Thumbnails */}
                {product.gallery?.map((image: string, index: number) => (
                  <div 
                    key={index}
                    onClick={() => {
                      setActiveImageIndex(index);
                      setActiveView('images');
                    }}
                    className={`w-20 h-20 bg-gray-100 rounded-lg overflow-hidden cursor-pointer hover:opacity-75 transition-all border-2 ${
                      activeView === 'images' && activeImageIndex === index 
                        ? 'border-red-500 ring-2 ring-red-200' 
                        : 'border-transparent hover:border-red-300'
                    }`}
                  >
                    <SafeImage
                      src={image}
                      alt={`${product.name} thumbnail ${index + 1}`}
                      width={100}
                      height={100}
                      className="object-cover w-full h-full"
                    />
                  </div>
                ))}
                
                {/* 3D View Button */}
                <div 
                  onClick={() => setActiveView('3d')}
                  className={`w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg cursor-pointer hover:opacity-75 transition-all border-2 flex items-center justify-center ${
                    activeView === '3d' ? 'border-red-500 ring-2 ring-red-200' : 'border-transparent hover:border-red-300'
                  }`}
                >
                  <div className="text-center">
                    <div className="mb-1 text-sm text-white">🌐</div>
                    <div className="text-xs font-semibold text-white">3D</div>
                  </div>
                </div>
              </div>

              {/* View Toggle Buttons */}
              <div className="space-y-3">
                <div className="flex space-x-2">
                  <button
                    onClick={() => setActiveView('images')}
                    className={`px-4 py-2 rounded-lg font-medium transition-colors ${
                      activeView === 'images' 
                        ? 'bg-red-600 text-white' 
                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                    }`}
                  >
                    📷 Images ({product.gallery?.length || 0})
                  </button>
                  <button
                    onClick={() => setActiveView('3d')}
                    className={`px-4 py-2 rounded-lg font-medium transition-colors ${
                      activeView === '3d' 
                        ? 'bg-red-600 text-white' 
                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                    }`}
                  >
                    🌐 3D View
                  </button>
                </div>
                <div className="p-2 text-xs text-gray-500 border border-yellow-200 rounded-lg bg-yellow-50">
                  <span className="font-semibold">Catatan:</span> Warna produk aktual mungkin sedikit berbeda dari gambar yang ditampilkan. Pilih warna di bawah untuk melihat varian yang tersedia.
                </div>
              </div>
            </div>

            {/* Product Info Section */}
            <div className="space-y-6">
              {/* Product Header */}
              <div>
                <div className="flex items-center mb-2 space-x-2">
                  <span className="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                    {product.serial}
                  </span>
                  {product.salePrice && product.originalPrice && product.salePrice < product.originalPrice && (
                    <span className="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                      {Math.round(((product.originalPrice - product.salePrice) / product.originalPrice) * 100)}% OFF
                    </span>
                  )}
                  {product.inStock ? (
                    <span className="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                      In Stock
                    </span>
                  ) : (
                    <span className="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                      Out of Stock
                    </span>
                  )}
                </div>
                <h1 className="mb-2 text-3xl font-bold text-gray-900">{product.name}</h1>
                <p className="mb-4 text-lg text-gray-600">{product.description}</p>
                
                {/* Price */}
                <div className="flex items-baseline mb-4 space-x-2">
                  <span className="text-3xl font-bold text-red-600">{product.price}</span>
                  {product.salePrice && product.originalPrice && product.salePrice < product.originalPrice && (
                    <span className="text-lg text-gray-500 line-through">
                      Rp. {(product.originalPrice).toLocaleString('id-ID')}
                    </span>
                  )}
                </div>
              </div>

              {/* Product Specs Quick Info */}
              <div className="grid grid-cols-2 gap-4 p-4 rounded-lg bg-gray-50">
                <div>
                  <div className="text-sm text-gray-500">Berat</div>
                  <div className="font-semibold">{product.weight}</div>
                </div>
                <div>
                  <div className="text-sm text-gray-500">Asal</div>
                  <div className="font-semibold">{product.countryOrigin}</div>
                </div>
                <div>
                  <div className="text-sm text-gray-500">Garansi</div>
                  <div className="font-semibold">{product.warranty}</div>
                </div>
                <div>
                  <div className="text-sm text-gray-500">Rilis</div>
                  <div className="font-semibold">{new Date(product.releaseDate).toLocaleDateString('id-ID')}</div>
                </div>
              </div>

              {/* Color Selection */}
              {product.colors && product.colors.length > 0 && (
                <div className="space-y-4">
                  <div>
                    <h3 className="mb-3 text-lg font-semibold text-gray-900">Pilih Warna</h3>
                    <div className="flex items-center gap-2 p-3 mb-4 rounded-lg bg-gray-50">
                      <span className="text-sm text-gray-600">Warna saat ini:</span>
                      <span className="inline-flex items-center px-3 py-1 text-sm font-semibold text-red-700 bg-red-100 rounded-full">
                        {selectedColor}
                      </span>
                    </div>
                  </div>
                  <div className="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-2 xl:grid-cols-3">
                    {product.colors.map((color: string) => (
                      <button
                        key={color}
                        onClick={() => {
                          setSelectedColor(color);
                          // Reset to first image when color changes
                          setActiveImageIndex(0);
                        }}
                        className={`relative px-4 py-3 text-sm font-medium text-center border rounded-xl transition-all duration-200 ${
                          selectedColor === color
                            ? 'border-red-500 bg-red-50 text-red-700 ring-2 ring-red-200 shadow-md'
                            : 'border-gray-300 bg-white text-gray-700 hover:border-red-300 hover:bg-red-50 hover:shadow-sm'
                        }`}
                      >
                        {selectedColor === color && (
                          <span className="absolute flex items-center justify-center w-5 h-5 text-xs text-white bg-red-500 rounded-full top-1 right-1">
                            ✓
                          </span>
                        )}
                        <span className="block">{color}</span>
                      </button>
                    ))}
                  </div>
                </div>
              )}

              {/* Size Selection */}
              <div className="space-y-4">
                <div>
                  <h3 className="mb-3 text-lg font-semibold text-gray-900">Pilih Ukuran</h3>
                  {selectedSize && (
                    <div className="flex items-center gap-2 p-3 mb-4 rounded-lg bg-gray-50">
                      <span className="text-sm text-gray-600">Ukuran dipilih:</span>
                      <span className="inline-flex items-center px-3 py-1 text-sm font-semibold text-blue-700 bg-blue-100 rounded-full">
                        {selectedSize}
                      </span>
                    </div>
                  )}
                </div>
                <div className="grid grid-cols-6 gap-2 sm:gap-3">
                  {product.sizes.map((size: string | number) => (
                    <button
                      key={size}
                      onClick={() => setSelectedSize(size)}
                      className={`aspect-square rounded-xl border font-semibold text-sm transition-all duration-200 ${
                        selectedSize === size
                          ? 'border-red-500 bg-red-50 text-red-700 ring-2 ring-red-200 shadow-md'
                          : 'border-gray-300 bg-white text-gray-700 hover:border-red-300 hover:bg-red-50 hover:shadow-sm'
                      }`}
                    >
                      {size}
                    </button>
                  ))}
                </div>
              </div>

              {/* Quantity and Add to Cart */}
              <div className="space-y-6">
                <div className="space-y-4">
                  <h3 className="text-lg font-semibold text-gray-900">Jumlah</h3>
                  <div className="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                    <span className="text-sm font-medium text-gray-600">Qty:</span>
                    <div className="flex items-center gap-3">
                      <button
                        onClick={() => setQuantity(Math.max(1, quantity - 1))}
                        className="flex items-center justify-center w-10 h-10 text-gray-600 transition-all duration-200 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:border-gray-400"
                      >
                        −
                      </button>
                      <span className="flex items-center justify-center w-16 h-10 text-xl font-bold text-gray-900 bg-white border border-gray-300 rounded-lg">
                        {quantity}
                      </span>
                      <button
                        onClick={() => setQuantity(quantity + 1)}
                        className="flex items-center justify-center w-10 h-10 text-gray-600 transition-all duration-200 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:border-gray-400"
                      >
                        +
                      </button>
                    </div>
                  </div>
                </div>

                <button
                  onClick={handleAddToCart}
                  disabled={!product.inStock || !selectedSize || isAddingToCart}
                  className={`w-full py-4 px-6 rounded-xl font-bold text-lg transition-all duration-200 ${
                    product.inStock && selectedSize && !isAddingToCart
                      ? 'bg-red-600 text-white hover:bg-red-700 hover:shadow-lg'
                      : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                  }`}
                >
                  {isAddingToCart
                    ? '🔄 Menambahkan...'
                    : !product.inStock 
                    ? 'Out of Stock' 
                    : !selectedSize 
                    ? 'Pilih Ukuran Dulu'
                    : '🛒 Add to Cart'
                  }
                </button>
              </div>
            </div>
          </div>

          {/* Product Detail Description */}
          <div className="pt-8 mt-12 border-t">
            <h2 className="mb-6 text-2xl font-bold">Detail Produk</h2>
            <div 
              className="prose prose-lg max-w-none"
              dangerouslySetInnerHTML={{ __html: product.detailedDescription }}
              style={{
                '--tw-prose-headings': '#1f2937',
                '--tw-prose-body': '#374151',
                '--tw-prose-bold': '#1f2937',
                '--tw-prose-links': '#dc2626'
              } as React.CSSProperties}
            />
          </div>
        </div>
      </div>

      {/* Modals */}
      <CartModal
        isOpen={showCartModal}
        onClose={() => setShowCartModal(false)}
        cartItems={cartItems}
        onUpdateQuantity={handleUpdateCartQuantity}
        onRemoveItem={handleRemoveCartItem}
      />
      
      <TransactionModal
        isOpen={showTransactionModal}
        onClose={() => setShowTransactionModal(false)}
        transactions={transactions}
      />

      {/* Footer */}
      <footer className="py-12 mt-16 text-white bg-red-800">
        <div className="container px-4 mx-auto">
          <div className="grid grid-cols-1 gap-8 md:grid-cols-3">
            {/* Brand */}
            <div>
              <h3 className="mb-4 text-2xl font-bold">VNY</h3>
              <p className="mb-2 text-sm text-white/80">+62 123 456 789</p>
              <p className="text-sm text-white/80">VNY@gmail.com</p>
              
              <div className="flex mt-4 space-x-4">
                <div className="flex items-center justify-center w-8 h-8 rounded-full cursor-pointer bg-white/20 hover:bg-white/30">
                  <span className="text-sm">f</span>
                </div>
                <div className="flex items-center justify-center w-8 h-8 rounded-full cursor-pointer bg-white/20 hover:bg-white/30">
                  <span className="text-sm">t</span>
                </div>
              </div>
            </div>

            {/* Quick Action */}
            <div>
              <h4 className="mb-4 font-semibold">Quick Action</h4>
              <div className="space-y-2 text-sm text-white/80">
                <div>Product</div>
                <div>Gallery</div>
              </div>
            </div>

            <div>
              <div className="space-y-2 text-sm text-white/80">
                <div>Our Group</div>
                <div>Contact VNY</div>
              </div>
            </div>
          </div>

          <div className="pt-8 mt-8 text-sm text-center border-t border-white/20 text-white/60">
            © 2025 VNY. All rights reserved
          </div>
        </div>
      </footer>
    </div>
  );
};

export default ProductDetail;