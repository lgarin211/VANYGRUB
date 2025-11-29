'use client';

import React, { useState, useEffect } from 'react';
import Image from 'next/image';
import Link from 'next/link';
import { useParams } from 'next/navigation';
import productsData from '../../../constants/productsDataDetailed.json';
import CartModal from '../../../components/CartModal';
import TransactionModal from '../../../components/TransactionModal';
import '../../../styles/product-detail.css';

interface Product {
  id: number;
  name: string;
  category: string;
  price: string;
  originalPrice: number;
  discount?: string;
  images: string[];
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
  size: number;
  quantity: number;
}

interface TransactionItem {
  id: number;
  name: string;
  image: string;
  quantity: number;
  price: string;
  color: string;
  size: number;
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
  const [selectedSize, setSelectedSize] = useState<number | null>(null);
  const [selectedColor, setSelectedColor] = useState<string>('');
  
  // Modal states
  const [showCartModal, setShowCartModal] = useState(false);
  const [showTransactionModal, setShowTransactionModal] = useState(false);
  const [showCartPreview, setShowCartPreview] = useState(false);
  const [showTransactionPreview, setShowTransactionPreview] = useState(false);
  
  // Cart and transaction data
  const [cartItems, setCartItems] = useState<CartItem[]>([
    {
      id: 1,
      name: "Air Jordan 1 Retro",
      price: "Rp. 5.000.000",
      originalPrice: 5000000,
      image: "/temp/nike-just-do-it(6).jpg",
      color: "Black/Red",
      size: 42,
      quantity: 1
    },
    {
      id: 2,
      name: "Nike Dunk Low",
      price: "Rp. 4.500.000",
      originalPrice: 4500000,
      image: "/temp/nike-just-do-it(7).jpg",
      color: "Panda (Black/White)",
      size: 43,
      quantity: 2
    }
  ]);
  
  const [transactions] = useState<Transaction[]>([
    {
      id: "ORD-2024-001",
      date: "2024-01-15",
      status: "delivered",
      items: [
        {
          id: 1,
          name: "Air Jordan 1 Retro",
          image: "/temp/nike-just-do-it(6).jpg",
          quantity: 1,
          price: "Rp. 5.000.000",
          color: "Black/Red",
          size: 42
        }
      ],
      total: 5550000,
      shippingAddress: "Jl. Sudirman No. 123, Jakarta Pusat",
      paymentMethod: "Credit Card **** 1234",
      trackingNumber: "JNE123456789"
    },
    {
      id: "ORD-2024-002",
      date: "2024-02-10",
      status: "shipped",
      items: [
        {
          id: 2,
          name: "Nike Dunk Low",
          image: "/temp/nike-just-do-it(7).jpg",
          quantity: 1,
          price: "Rp. 4.500.000",
          color: "Panda",
          size: 43
        },
        {
          id: 3,
          name: "Converse Chuck 70",
          image: "/temp/nike-just-do-it(8).jpg",
          quantity: 1,
          price: "Rp. 3.800.000",
          color: "Vintage Khaki",
          size: 41
        }
      ],
      total: 9130000,
      shippingAddress: "Jl. Thamrin No. 456, Jakarta Selatan",
      paymentMethod: "Bank Transfer BCA",
      trackingNumber: "TIKI987654321"
    }
  ]);

  // Find product by ID
  const product: Product | undefined = productsData.products.find(p => p.id === productId);

  useEffect(() => {
    if (product && product.colors.length > 0) {
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

  // Keyboard navigation
  useEffect(() => {
    const handleKeyDown = (event: KeyboardEvent) => {
      if (activeView === 'images' && product && product.images.length > 1) {
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
  }, [activeView, activeImageIndex, product]);

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

  if (!product) {
    return (
      <div className="flex items-center justify-center min-h-screen">
        <div className="text-center">
          <h1 className="mb-4 text-2xl font-bold text-gray-900">Produk tidak ditemukan</h1>
          <Link href="/product" className="text-red-600 hover:text-red-800">
            ← Kembali ke Produk
          </Link>
        </div>
      </div>
    );
  }

  const handleAddToCart = () => {
    if (!selectedSize) {
      alert('Pilih ukuran terlebih dahulu!');
      return;
    }
    
    const newItem: CartItem = {
      id: product.id,
      name: product.name,
      price: product.price,
      originalPrice: product.originalPrice,
      image: getCurrentImage(),
      color: selectedColor,
      size: selectedSize,
      quantity: quantity
    };
    
    setCartItems(prev => {
      const existingItemIndex = prev.findIndex(item => 
        item.id === newItem.id && item.color === newItem.color && item.size === newItem.size
      );
      
      if (existingItemIndex > -1) {
        // Update quantity if item exists
        const updatedItems = [...prev];
        updatedItems[existingItemIndex].quantity += newItem.quantity;
        return updatedItems;
      } else {
        // Add new item
        return [...prev, newItem];
      }
    });
    
    alert(`✅ ${quantity} ${product.name} (${selectedColor}, Size ${selectedSize}) berhasil ditambahkan ke keranjang!`);
  };
  
  const handleUpdateCartQuantity = (id: number, quantity: number) => {
    setCartItems(prev => 
      prev.map(item => item.id === id ? { ...item, quantity } : item)
    );
  };
  
  const handleRemoveCartItem = (id: number) => {
    setCartItems(prev => prev.filter(item => item.id !== id));
  };

  const handleImageNavigation = (direction: 'prev' | 'next') => {
    if (!product || product.images.length <= 1) return;
    
    if (direction === 'prev') {
      const newIndex = activeImageIndex === 0 ? product.images.length - 1 : activeImageIndex - 1;
      setActiveImageIndex(newIndex);
    } else {
      const newIndex = activeImageIndex === product.images.length - 1 ? 0 : activeImageIndex + 1;
      setActiveImageIndex(newIndex);
    }
    
    // Ensure we're in image view when navigating
    setActiveView('images');
  };

  // Get current image to display
  const getCurrentImage = () => {
    if (!product) return '';
    return product.images[activeImageIndex] || product.images[0];
  };

  return (
    <div className="min-h-screen bg-gray-100">
      {/* Header */}
      <header className="py-4 text-white bg-red-800">
        <div className="container flex items-center justify-between px-4 mx-auto">
          {/* Search Bar */}
          <div className="flex items-center">
            <div className="mr-8">
              <input
                type="text"
                placeholder="Q Search"
                className="px-2 py-1 text-white bg-transparent border-b border-white placeholder-white/70 focus:outline-none focus:border-white/100"
              />
            </div>
          </div>

          {/* Logo */}
          <Link href="/" className="text-2xl font-bold">
            VNY
          </Link>

          {/* Navigation */}
          <div className="flex space-x-4">
            {/* Cart Button */}
            <div className="relative">
              <Link 
                href="/cart"
                className="relative flex items-center px-4 py-2 space-x-2 transition-all duration-300 rounded-lg hover:bg-white/10 hover:text-white group"
                onMouseEnter={() => setShowCartPreview(true)}
                onMouseLeave={() => setShowCartPreview(false)}
              >
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13v6a2 2 0 002 2h10a2 2 0 002-2v-6m-10 0V9a2 2 0 012-2h6a2 2 0 012 2v4" />
                </svg>
                <span className="font-medium">CART</span>
                {cartItems.length > 0 && (
                  <span className="absolute flex items-center justify-center w-5 h-5 text-xs font-bold text-red-800 bg-yellow-400 rounded-full -top-1 -right-1 animate-pulse">
                    {cartItems.reduce((sum, item) => sum + item.quantity, 0)}
                  </span>
                )}
              </Link>
              
              {/* Cart Hover Preview */}
              {showCartPreview && (
                <div className="absolute left-0 z-50 mt-2 transition-all duration-300 transform bg-white border border-gray-200 rounded-lg shadow-2xl top-full w-80">
                  <div className="p-4 border-b border-gray-100">
                    <h3 className="font-semibold text-gray-900">Keranjang Belanja</h3>
                    <p className="text-sm text-gray-500">{cartItems.length} item{cartItems.length !== 1 ? 's' : ''}</p>
                  </div>
                  <div className="overflow-y-auto max-h-60">
                    {cartItems.length === 0 ? (
                      <div className="p-6 text-center">
                        <div className="flex items-center justify-center w-16 h-16 mx-auto mb-3 bg-gray-100 rounded-full">
                          <svg className="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13v6a2 2 0 002 2h10a2 2 0 002-2v-6m-10 0V9a2 2 0 012-2h6a2 2 0 012 2v4" />
                          </svg>
                        </div>
                        <p className="text-sm text-gray-500">Keranjang kosong</p>
                      </div>
                    ) : (
                      <div className="p-4 space-y-3">
                        {cartItems.slice(0, 3).map((item, index) => (
                          <div key={index} className="flex items-center space-x-3">
                            <div className="w-12 h-12 overflow-hidden bg-gray-100 rounded-lg">
                              <Image
                                src={item.image}
                                alt={item.name}
                                width={48}
                                height={48}
                                className="object-cover w-full h-full"
                              />
                            </div>
                            <div className="flex-1 min-w-0">
                              <h4 className="text-sm font-medium text-gray-900 truncate">{item.name}</h4>
                              <p className="text-xs text-gray-500">{item.color} • Size {item.size}</p>
                              <p className="text-xs font-semibold text-red-600">{item.price}</p>
                            </div>
                            <span className="text-sm text-gray-500">×{item.quantity}</span>
                          </div>
                        ))}
                        {cartItems.length > 3 && (
                          <div className="pt-2 text-sm text-center text-gray-500 border-t">
                            +{cartItems.length - 3} item lainnya
                          </div>
                        )}
                      </div>
                    )}
                  </div>
                  <div className="p-4 border-t border-gray-100">
                    <Link 
                      href="/cart"
                      className="block w-full py-2 font-medium text-center text-white transition-colors bg-red-600 rounded-lg hover:bg-red-700"
                      onClick={() => setShowCartPreview(false)}
                    >
                      Lihat Keranjang
                    </Link>
                  </div>
                </div>
              )}
            </div>
            
            {/* Transaction Button */}
            <div className="relative">
              <button 
                className="relative flex items-center px-4 py-2 space-x-2 transition-all duration-300 rounded-lg hover:bg-white/10 hover:text-white group"
                onClick={() => setShowTransactionModal(true)}
                onMouseEnter={() => setShowTransactionPreview(true)}
                onMouseLeave={() => setShowTransactionPreview(false)}
              >
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span className="font-medium">TRANSACTION</span>
                {transactions.length > 0 && (
                  <span className="absolute flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-blue-400 rounded-full -top-1 -right-1">
                    {transactions.length}
                  </span>
                )}
              </button>
              
              {/* Transaction Hover Preview */}
              {showTransactionPreview && (
                <div className="absolute right-0 z-50 mt-2 transition-all duration-300 transform bg-white border border-gray-200 rounded-lg shadow-2xl top-full w-80">
                  <div className="p-4 border-b border-gray-100">
                    <h3 className="font-semibold text-gray-900">Riwayat Transaksi</h3>
                    <p className="text-sm text-gray-500">{transactions.length} transaksi</p>
                  </div>
                  <div className="overflow-y-auto max-h-60">
                    {transactions.length === 0 ? (
                      <div className="p-6 text-center">
                        <div className="flex items-center justify-center w-16 h-16 mx-auto mb-3 bg-gray-100 rounded-full">
                          <svg className="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                          </svg>
                        </div>
                        <p className="text-sm text-gray-500">Belum ada transaksi</p>
                      </div>
                    ) : (
                      <div className="p-4 space-y-3">
                        {transactions.slice(0, 3).map((transaction, index) => {
                          const getStatusColor = (status: string) => {
                            switch (status) {
                              case 'pending': return 'bg-yellow-100 text-yellow-700 border-yellow-200';
                              case 'processing': return 'bg-blue-100 text-blue-700 border-blue-200';
                              case 'shipped': return 'bg-purple-100 text-purple-700 border-purple-200';
                              case 'delivered': return 'bg-green-100 text-green-700 border-green-200';
                              case 'cancelled': return 'bg-red-100 text-red-700 border-red-200';
                              default: return 'bg-gray-100 text-gray-700 border-gray-200';
                            }
                          };
                          
                          const getStatusText = (status: string) => {
                            switch (status) {
                              case 'pending': return 'Menunggu';
                              case 'processing': return 'Diproses';
                              case 'shipped': return 'Dikirim';
                              case 'delivered': return 'Selesai';
                              case 'cancelled': return 'Dibatalkan';
                              default: return 'Unknown';
                            }
                          };
                          
                          return (
                            <div key={index} className="p-3 border border-gray-100 rounded-lg">
                              <div className="flex items-center justify-between mb-2">
                                <span className="text-sm font-medium text-gray-900">#{transaction.id.slice(-6)}</span>
                                <span className={`px-2 py-1 rounded-full text-xs font-medium border ${getStatusColor(transaction.status)}`}>
                                  {getStatusText(transaction.status)}
                                </span>
                              </div>
                              <div className="flex items-center justify-between">
                                <div className="flex -space-x-1">
                                  {transaction.items.slice(0, 3).map((item, itemIndex) => (
                                    <div key={itemIndex} className="w-6 h-6 overflow-hidden bg-gray-100 border-2 border-white rounded-full">
                                      <Image
                                        src={item.image}
                                        alt={item.name}
                                        width={24}
                                        height={24}
                                        className="object-cover w-full h-full"
                                      />
                                    </div>
                                  ))}
                                  {transaction.items.length > 3 && (
                                    <div className="flex items-center justify-center w-6 h-6 bg-gray-200 border-2 border-white rounded-full">
                                      <span className="text-xs text-gray-600">+</span>
                                    </div>
                                  )}
                                </div>
                                <span className="text-sm font-semibold text-red-600">
                                  Rp {transaction.total.toLocaleString('id-ID')}
                                </span>
                              </div>
                            </div>
                          );
                        })}
                        {transactions.length > 3 && (
                          <div className="pt-2 text-sm text-center text-gray-500 border-t">
                            +{transactions.length - 3} transaksi lainnya
                          </div>
                        )}
                      </div>
                    )}
                  </div>
                  <div className="p-4 border-t border-gray-100">
                    <button 
                      className="w-full py-2 font-medium text-white transition-colors bg-red-600 rounded-lg hover:bg-red-700"
                      onClick={() => {
                        setShowTransactionPreview(false);
                        setShowTransactionModal(true);
                      }}
                    >
                      Lihat Semua Transaksi
                    </button>
                  </div>
                </div>
              )}
            </div>
          </div>
        </div>

        {/* Navigation Menu */}
        <nav className="container px-4 mx-auto mt-4">
          <div className="flex space-x-8">
            <Link href="/" className="hover:text-gray-300">HOME</Link>
            <Link href="/product" className="pb-1 border-b-2 border-white">PRODUCT</Link>
            <Link href="/about" className="hover:text-gray-300">ABOUT VNY</Link>
            <Link href="/gallery" className="hover:text-gray-300">GALLERY</Link>
          </div>
        </nav>
      </header>

      <div className="container px-4 py-8 mx-auto">
        {/* Breadcrumb */}
        <div className="mb-6">
          <Link href="/product" className="flex items-center text-red-600 hover:text-red-800">
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
                    <Image
                      src={getCurrentImage()}
                      alt={`${product.name} - ${selectedColor} - Image ${activeImageIndex + 1}`}
                      width={600}
                      height={600}
                      className="object-contain w-full h-full p-8 transition-all duration-300"
                      key={activeImageIndex} // Force re-render when image changes
                    />
                    
                    {/* Image Navigation Arrows */}
                    {product.images.length > 1 && (
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
                      {activeImageIndex + 1} / {product.images.length}
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
                {product.images.map((image, index) => (
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
                    <Image
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
                    📷 Images ({product.images.length})
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
                  {product.discount && (
                    <span className="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                      {product.discount} OFF
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
                  {product.discount && (
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
                    {product.colors.map((color) => (
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
                  {product.sizes.map((size) => (
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
                  disabled={!product.inStock || !selectedSize}
                  className={`w-full py-4 px-6 rounded-xl font-bold text-lg transition-all duration-200 ${
                    product.inStock && selectedSize
                      ? 'bg-red-600 text-white hover:bg-red-700 hover:shadow-lg'
                      : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                  }`}
                >
                  {!product.inStock 
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
              dangerouslySetInnerHTML={{ __html: product.detailDescription }}
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