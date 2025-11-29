'use client';

import React, { useState, useEffect } from 'react';
import Image from 'next/image';
import homeData from '../constants/dataHome.json';

interface ProductGridProps {
  title?: string;
}

const ProductGrid: React.FC<ProductGridProps> = ({ title }) => {
  // State untuk ukuran dinamis setiap card
  const [cardSizes, setCardSizes] = useState<number[]>([]);
  
  // Generate ukuran random setiap kali component mount
  useEffect(() => {
    const config = homeData.productGrid.sizeConfig;
    const sizes = [
      Math.floor(Math.random() * (config.maxHeight - config.minHeight)) + config.minHeight,
      Math.floor(Math.random() * (config.maxHeight - config.minHeight)) + config.minHeight,
      Math.floor(Math.random() * (config.maxHeight - config.minHeight)) + config.minHeight,
      Math.floor(Math.random() * (config.maxHeight - config.minHeight)) + config.minHeight
    ];
    setCardSizes(sizes);
    
    // Update ukuran setiap interval dengan animasi bertahap
    const interval = setInterval(() => {
      const newSizes = [
        Math.floor(Math.random() * (config.maxHeight - config.minHeight)) + config.minHeight,
        Math.floor(Math.random() * (config.maxHeight - config.minHeight)) + config.minHeight,
        Math.floor(Math.random() * (config.maxHeight - config.minHeight)) + config.minHeight,
        Math.floor(Math.random() * (config.maxHeight - config.minHeight)) + config.minHeight
      ];
      setCardSizes(newSizes);
    }, config.animationInterval);

    return () => clearInterval(interval);
  }, []);

  // Load data from JSON
  const products = homeData.productGrid.products;

  return (
    <section className="bg-gray-100 py-16">
      <div className="container mx-auto px-4">
        {/* Grid Layout - 2x2 dengan konten sesuai gambar referensi */}
        <div className="flex flex-wrap justify-center items-center gap-4 max-w-7xl mx-auto mb-16">
          {products.map((product, index) => (
            <div 
              key={product.id}
              className={`relative rounded-xl overflow-hidden group cursor-pointer transform hover:scale-105 transition-all duration-[3000ms] ease-in-out ${product.bgImage} ${product.bgColor}`}
              style={{ 
                height: `${cardSizes[index] || 320}px`,
                width: `${Math.min(500, Math.max(350, (cardSizes[index] || 320) * 1.1))}px`,
                flex: '1 1 calc(50% - 8px)'
              }}
              data-aos={index % 2 === 0 ? "fade-right" : "fade-left"}
              data-aos-delay={`${(index + 1) * 100}`}
            >
              {/* Product image - Full size background */}
              <div className="absolute inset-0 transition-all duration-[3000ms] ease-in-out">
                <Image
                  src={product.image}
                  alt={product.title}
                  fill
                  className="object-cover group-hover:scale-105 transition-transform duration-500"
                  sizes="500px"
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