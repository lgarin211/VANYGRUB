'use client';

import React, { useState, useEffect } from 'react';
import { useHomeData } from '../hooks/useApi';
import SafeImage from './SafeImage';

interface ProductGridProps {
  title?: string;
}

const ProductGrid: React.FC<ProductGridProps> = ({ title }) => {
  // State untuk ukuran dinamis setiap card
  const [cardSizes, setCardSizes] = useState<number[]>([]);
  
  // Load data from API with fallback
  const { data: homeData, loading } = useHomeData();
  
  // Static fallback data for ProductGrid
  const fallbackProducts = [
    {
      id: 1,
      title: "Premium Collection",
      subtitle: "Sneakers Motif Ulu Paung",
      image: "/temp/nike-just-do-it(6).jpg",
      bgColor: "from-red-600 to-red-800",
      buttonText: "Shop Now"
    },
    {
      id: 2,
      title: "Traditional Style",
      subtitle: "Gorga Pattern Collection",
      image: "/temp/nike-just-do-it(7).jpg",
      bgColor: "from-gray-800 to-black",
      buttonText: "Explore"
    },
    {
      id: 3,
      title: "Modern Design",
      subtitle: "Contemporary Ethnic Wear",
      image: "/temp/nike-just-do-it(8).jpg",
      bgColor: "from-blue-600 to-blue-800",
      buttonText: "Discover"
    }
  ];
  
  // Debug logging for ProductGrid data
  useEffect(() => {
    console.log('ProductGrid DEBUG:', {
      loading,
      hasHomeData: !!homeData,
      productGrid: homeData?.productGrid,
      productGridItems: homeData?.productGrid?.items,
      itemsCount: homeData?.productGrid?.items?.length || 0
    });
  }, [loading, homeData]);
  
  // Generate ukuran random setiap kali component mount
  useEffect(() => {
    const products = homeData?.productGrid?.items || fallbackProducts;
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
  }, [loading, homeData?.productGrid?.items?.length, fallbackProducts.length]);

  // Use API data or fallback
  const products = homeData?.productGrid?.items || fallbackProducts;

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
      </div>
    </section>
  );
};

export default ProductGrid;