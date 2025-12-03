'use client';

import React, { useState, useEffect } from 'react';
import { useHomeData } from '../hooks/useApi';
import SafeImage from './SafeImage';
import Link from 'next/link';

interface ProductGridProps {
  title?: string;
}

const ProductGrid: React.FC<ProductGridProps> = ({ title }) => {
  // State untuk ukuran dinamis setiap card
  const [cardSizes, setCardSizes] = useState<number[]>([]);
  const [selectedProduct, setSelectedProduct] = useState<any>(null);
  const [isModalOpen, setIsModalOpen] = useState(false);

  const openProductModal = (product: any) => {
    setSelectedProduct(product);
    setIsModalOpen(true);
  };

  const closeProductModal = () => {
    setIsModalOpen(false);
    setSelectedProduct(null);
  };
  
  // Load data from API
  const { data: homeData, loading } = useHomeData();
  
  // Debug logging for ProductGrid data
  useEffect(() => {
    console.log('ProductGrid DEBUG:', {
      loading,
      hasHomeData: !!homeData,
      productGrid: homeData?.productGrid,
      productGridItems: homeData?.productGrid?.items,
      itemsCount: homeData?.productGrid?.items?.length || 0,
      sampleItem: homeData?.productGrid?.items?.[0]
    });
  }, [loading, homeData]);
  
  // Generate ukuran random setiap kali component mount
  useEffect(() => {
    const products = homeData?.productGrid?.items || [];
    if (loading || !products.length) return;
    
    // Gunakan default config karena struktur sizeConfig sudah berubah
    const defaultConfig = {
      minHeight: 300,
      maxHeight: 380,
      animationInterval: 8000
    };
    
    const itemCount = products.length;
    const sizes = Array.from({ length: itemCount }, () => 
      Math.floor(Math.random() * (defaultConfig.maxHeight - defaultConfig.minHeight)) + defaultConfig.minHeight
    );
    setCardSizes(sizes);
    
    // Update ukuran setiap interval dengan animasi bertahap
    const interval = setInterval(() => {
      const newSizes = Array.from({ length: itemCount }, () =>
        Math.floor(Math.random() * (defaultConfig.maxHeight - defaultConfig.minHeight)) + defaultConfig.minHeight
      );
      setCardSizes(newSizes);
    }, defaultConfig.animationInterval);

    return () => clearInterval(interval);
  }, [loading, homeData?.productGrid?.items?.length]);

  // Use API data only
  const products = homeData?.productGrid?.items || [];

  // Only show loading if we have no products at all (including fallback)
  if (loading && (!products || products.length === 0)) {
    return (
      <section className="bg-gray-100 py-16">
        <div className="container mx-auto px-4">
          <div className="text-center">Loading products...</div>
        </div>
      </section>
    );
  }
  
  // Don't show section if no products available
  if (!products || products.length === 0) {
    return null;
  }
  
  return (
    <section className="bg-gray-100 py-16">
      <div className="container mx-auto px-4">
        {/* Responsive Grid Layout */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-w-7xl mx-auto mb-16">
          {products.map((product: any, index: number) => (
            <div 
              key={product.id}
              className={`relative rounded-xl overflow-hidden group cursor-pointer transform hover:scale-105 transition-all duration-[3000ms] ease-in-out bg-gradient-to-br ${product.bgColor}`}
              style={{ 
                height: `${cardSizes[index] || 320}px`,
                minHeight: '300px'
              }}
              data-aos={index % 2 === 0 ? "fade-right" : "fade-left"}
              data-aos-delay={`${(index + 1) * 100}`}
              onClick={() => openProductModal(product)}
            >
              {/* Product image - Full size background */}
              <div className="absolute inset-0 transition-all duration-[3000ms] ease-in-out">
                <SafeImage
                  src={product.image}
                  alt={product.title}
                  fill
                  className="object-cover group-hover:scale-105 transition-transform duration-500"
                  sizes="500px"
                  fallbackSrc="/temp/placeholder-image.svg"
                />
              </div>
              
              {/* Gradient overlay for better text contrast */}
              <div className="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
              
              {/* Content */}
              <div className="absolute left-6 bottom-6 right-6 z-10 transition-all duration-[3000ms] ease-in-out">
                <div 
                  className="text-white/80 font-medium mb-2 tracking-wider transition-all duration-[3000ms] ease-in-out"
                  style={{ fontSize: `${Math.max(12, (cardSizes[index] || 320) * 0.035)}px` }}
                >
                  {product.title}
                </div>
                <h3 
                  className="text-white font-bold mb-4 leading-tight transition-all duration-[3000ms] ease-in-out"
                  style={{ fontSize: `${Math.max(18, (cardSizes[index] || 320) * 0.065)}px` }}
                >
                  {product.subtitle}
                </h3>
                <button 
                  className="bg-white text-gray-900 rounded-full font-bold hover:bg-gray-100 transition-all duration-300 shadow-lg"
                  style={{ 
                    padding: `${Math.max(6, (cardSizes[index] || 320) * 0.02)}px ${Math.max(20, (cardSizes[index] || 320) * 0.06)}px`,
                    fontSize: `${Math.max(12, (cardSizes[index] || 320) * 0.04)}px`
                  }}
                >
                  {product.buttonText}
                </button>
              </div>
              
              {/* Decorative elements */}
              <div 
                className="absolute bg-white/30 rounded-full transition-all duration-[3000ms] ease-in-out" 
                style={{ 
                  top: `${(cardSizes[index] || 320) * 0.08}px`,
                  right: `${(cardSizes[index] || 320) * 0.05}px`,
                  width: `${Math.max(6, (cardSizes[index] || 320) * 0.02)}px`,
                  height: `${Math.max(6, (cardSizes[index] || 320) * 0.02)}px`
                }}
              ></div>
              <div 
                className="absolute bg-white/20 rounded-full transition-all duration-[3000ms] ease-in-out" 
                style={{ 
                  top: `${(cardSizes[index] || 320) * 0.15}px`,
                  right: `${(cardSizes[index] || 320) * 0.07}px`,
                  width: `${Math.max(3, (cardSizes[index] || 320) * 0.01)}px`,
                  height: `${Math.max(3, (cardSizes[index] || 320) * 0.01)}px`
                }}
              ></div>
              <div 
                className="absolute bg-white/25 rounded-full transition-all duration-[3000ms] ease-in-out" 
                style={{ 
                  bottom: `${(cardSizes[index] || 320) * 0.15}px`,
                  left: `${(cardSizes[index] || 320) * 0.05}px`,
                  width: `${Math.max(4, (cardSizes[index] || 320) * 0.015)}px`,
                  height: `${Math.max(4, (cardSizes[index] || 320) * 0.015)}px`
                }}
              ></div>
            </div>
          ))}
        </div>

        {/* Product Detail Modal */}
        {isModalOpen && selectedProduct && (
          <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-75 backdrop-blur-sm">
            <div className="relative w-full max-w-4xl max-h-[90vh] overflow-hidden bg-white rounded-3xl shadow-2xl transform transition-all duration-500 scale-100 animate-in">
              {/* Close Button */}
              <button
                onClick={closeProductModal}
                className="absolute z-20 flex items-center justify-center w-12 h-12 text-white transition-all duration-300 bg-black rounded-full top-6 right-6 bg-opacity-70 hover:bg-opacity-90 hover:rotate-90"
              >
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>

              <div className="flex flex-col lg:flex-row h-full">
                {/* Hero Image Section */}
                <div className="relative lg:w-1/2 h-64 lg:h-auto bg-gradient-to-br from-gray-100 to-gray-200">
                  <SafeImage 
                    src={selectedProduct.image} 
                    alt={selectedProduct.title} 
                    fill
                    className="object-cover"
                  />
                  <div className={`absolute inset-0 bg-gradient-to-t ${selectedProduct.bgColor} opacity-20`}></div>
                  
                  {/* Floating Elements */}
                  <div className="absolute top-8 left-8">
                    <div className="w-3 h-3 bg-white rounded-full opacity-60 animate-pulse"></div>
                  </div>
                  <div className="absolute top-16 right-12">
                    <div className="w-2 h-2 bg-white rounded-full opacity-40 animate-bounce"></div>
                  </div>
                  <div className="absolute bottom-12 left-12">
                    <div className="w-4 h-4 bg-white rounded-full opacity-30 animate-ping"></div>
                  </div>
                </div>

                {/* Content Section */}
                <div className="lg:w-1/2 p-8 lg:p-12 flex flex-col justify-center">
                  <div className="space-y-6">
                    {/* Category Badge */}
                    <div className="inline-block">
                      <span className="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-full">
                        {selectedProduct.title}
                      </span>
                    </div>

                    {/* Product Title */}
                    <h2 className="text-4xl font-bold text-gray-900 leading-tight">
                      {selectedProduct.subtitle}
                    </h2>

                    {/* Description */}
                    <p className="text-lg text-gray-600 leading-relaxed">
                      Temukan koleksi eksklusif {selectedProduct.subtitle.toLowerCase()} dengan desain autentik dan kualitas premium. 
                      Setiap produk dibuat dengan perhatian detail untuk memberikan pengalaman yang tak terlupakan.
                    </p>

                    {/* Features */}
                    <div className="grid grid-cols-2 gap-4">
                      <div className="flex items-center space-x-3">
                        <div className="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span className="text-sm text-gray-600">Premium Quality</span>
                      </div>
                      <div className="flex items-center space-x-3">
                        <div className="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <span className="text-sm text-gray-600">Authentic Design</span>
                      </div>
                      <div className="flex items-center space-x-3">
                        <div className="w-2 h-2 bg-purple-500 rounded-full"></div>
                        <span className="text-sm text-gray-600">Limited Edition</span>
                      </div>
                      <div className="flex items-center space-x-3">
                        <div className="w-2 h-2 bg-red-500 rounded-full"></div>
                        <span className="text-sm text-gray-600">Handcrafted</span>
                      </div>
                    </div>

                    {/* Action Buttons */}
                    <div className="flex flex-col sm:flex-row gap-4 pt-4">
                      <Link 
                        href="/vny/product"
                        className={`flex-1 px-8 py-4 font-bold text-white text-center rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl bg-gradient-to-r ${selectedProduct.bgColor}`}
                        onClick={closeProductModal}
                      >
                        {selectedProduct.buttonText}
                      </Link>
                      <button
                        className="px-8 py-4 font-semibold text-gray-700 transition-all duration-300 transform bg-gray-100 rounded-2xl hover:bg-gray-200 hover:scale-105"
                        onClick={closeProductModal}
                      >
                        Browse More
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              {/* Decorative Background Pattern */}
              <div className="absolute inset-0 pointer-events-none opacity-5">
                <div className="absolute top-10 left-10 w-32 h-32 border border-gray-300 rounded-full"></div>
                <div className="absolute bottom-10 right-10 w-24 h-24 border border-gray-300 rounded-full"></div>
                <div className="absolute top-1/2 left-1/4 w-16 h-16 border border-gray-300 rounded-full"></div>
              </div>
            </div>
          </div>
        )}
      </div>
    </section>
  );
};

export default ProductGrid;