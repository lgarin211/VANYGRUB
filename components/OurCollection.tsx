'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import { useHomeData } from '../hooks/useApi';
import SafeImage from './SafeImage';

const OurCollection: React.FC = () => {
  const [currentIndex, setCurrentIndex] = useState(0);
  
  // Load data from API with fallback
  const { data: homeData, loading } = useHomeData();
  const { title, subtitle, products: collections, carouselConfig } = homeData?.ourCollection || {
    title: "Our Collection",
    subtitle: "Discover our premium products",
    products: [],
    carouselConfig: { itemsPerView: 4, gap: 20 }
  };

  // Auto-play carousel if there are more than 4 items
  useEffect(() => {
    if (collections && collections.length > itemsPerView) {
      const interval = setInterval(() => {
        setCurrentIndex((prev) => {
          const newIndex = prev + 1;
          return newIndex >= Math.max(0, collections.length - itemsPerView) ? 0 : newIndex;
        });
      }, 4000); // Change slide every 4 seconds

      return () => clearInterval(interval);
    }
  }, [collections, itemsPerView]);



  const itemsPerView = 4; // Show 4 items at once
  const maxIndex = Math.max(0, (collections?.length || 0) - itemsPerView);

  const nextSlide = () => {
    if (collections && collections.length > itemsPerView) {
      setCurrentIndex((prev) => (prev >= maxIndex ? 0 : prev + 1));
    }
  };

  const prevSlide = () => {
    if (collections && collections.length > itemsPerView) {
      setCurrentIndex((prev) => (prev <= 0 ? maxIndex : prev - 1));
    }
  };

  // Show loading state only if no data at all
  if (loading && (!collections || collections.length === 0)) {
    return (
      <section className="py-16 bg-gray-100">
        <div className="container px-4 mx-auto">
          <div className="text-center">
            <div className="w-12 h-12 mx-auto border-b-2 border-red-600 rounded-full animate-spin"></div>
            <p className="mt-4 text-gray-600">Loading our collection...</p>
          </div>
        </div>
      </section>
    );
  }

  // Show empty state if no collections
  if (!collections || collections.length === 0) {
    return (
      <section className="py-16 bg-gray-100">
        <div className="container px-4 mx-auto">
          <div className="text-center">
            <h2 className="mb-4 text-3xl font-bold text-gray-800">{title}</h2>
            <p className="mb-8 text-gray-600">{subtitle}</p>
            <p className="text-gray-500">No collection items available.</p>
          </div>
        </div>
      </section>
    );
  }

  return (
    <section className="py-16 bg-gray-100">
      <div className="container px-4 mx-auto">
        {/* Section Title */}
        <div className="px-8 mb-8 text-left">
          <h2 
            className="text-3xl font-bold text-gray-800"
            data-aos="fade-up"
            data-aos-delay="100"
          >
            {title}
          </h2>
          <p className="mt-2 text-lg text-gray-600">{subtitle}</p>
        </div>

        {/* Carousel Container */}
        <div className="relative min-h-[220px]">
          {/* Navigation Buttons - Only show if there are more than 4 items */}
          {collections && collections.length > itemsPerView && (
            <>
              <button 
                onClick={prevSlide}
                className="absolute z-30 p-3 transition-all duration-300 -translate-y-1/2 border border-gray-200 rounded-full shadow-lg left-2 top-1/2 bg-white/90 hover:bg-white"
                data-aos="fade-right"
                data-aos-delay="400"
              >
                <svg className="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
              </button>
              
              <button 
                onClick={nextSlide}
                className="absolute z-30 p-3 transition-all duration-300 -translate-y-1/2 border border-gray-200 rounded-full shadow-lg right-2 top-1/2 bg-white/90 hover:bg-white"
                data-aos="fade-left"
                data-aos-delay="400"
              >
                <svg className="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7" />
                </svg>
              </button>
            </>
          )}

          {/* Collection Carousel - Clean Design */}
          <div className="relative px-8">
            <div className="overflow-hidden">
              <div 
                className="flex gap-5 transition-transform duration-500 ease-out"
                style={{ 
                  transform: collections && collections.length > 0 ? `translateX(-${currentIndex * 310}px)` : 'translateX(0)',
                  width: collections && collections.length > 0 ? `${collections.length * 310}px` : '100%'
                }}
              >
              {collections.map((item: any, index: number) => (
                <div 
                  key={item.id || index}
                  className="relative flex-shrink-0 overflow-hidden transition-all duration-300 transform bg-gray-800 shadow-lg cursor-pointer rounded-xl group hover:shadow-2xl hover:-translate-y-1"
                  style={{ 
                    minWidth: '290px',
                    width: '290px',
                    height: '200px'
                  }}
                  data-aos="zoom-in"
                  data-aos-delay={200 + index * 50}
                >
                  {/* Badge for special items */}
                  {item.badge && (
                    <div className="absolute z-30 px-2 py-1 text-xs font-medium text-white rounded-md top-3 right-3 bg-black/80 backdrop-blur-sm">
                      {item.badge}
                    </div>
                  )}
                  
                  {/* Product Image Container */}
                  <div className="relative w-full h-full overflow-hidden bg-gray-200 rounded-xl">
                    <SafeImage
                      src={item.image || '/temp/placeholder-image.svg'}
                      alt={item.name || 'Product Image'}
                      fill
                      className="object-cover transition-transform duration-500 group-hover:scale-110"
                      sizes="290px"
                      priority={index < 2}
                      fallbackSrc="/temp/placeholder-image.svg"
                    />
                    
                    {/* Dark overlay for better contrast */}
                    <div className="absolute inset-0 transition-colors duration-300 bg-black/25 group-hover:bg-black/15"></div>
                    
                    {/* Gradient overlay for text area */}
                    <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                  </div>
                  
                  {/* Product Name */}
                  <div className="absolute z-20 bottom-4 left-4 right-4">
                    <h3 className="text-xl font-bold tracking-wide text-white">
                      {item.name}
                    </h3>
                    <p className="mt-1 text-sm font-medium text-gray-300 opacity-80">
                      {item.price || 'Price not available'}
                    </p>
                  </div>
                  
                  {/* Corner accent */}
                  <div className="absolute top-0 left-0 w-12 h-12 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-white/10 to-transparent rounded-xl group-hover:opacity-100"></div>
                </div>
              ))
              </div>
            </div>
          </div>
        </div>

        {/* Look More Button */}
        <div className="mt-5 text-center">
          <Link 
            href="/collection"
            className="inline-flex items-center px-8 py-4 text-white transition-all duration-300 transform bg-red-800 rounded-full shadow-lg hover:bg-red-700 hover:scale-105"
            data-aos="fade-up"
            data-aos-delay="600"
          >
            Look More
            <svg 
              className="w-5 h-5 ml-2" 
              fill="none" 
              stroke="currentColor" 
              viewBox="0 0 24 24"
            >
              <path 
                strokeLinecap="round" 
                strokeLinejoin="round" 
                strokeWidth={2} 
                d="M9 5l7 7-7 7" 
              />
            </svg>
          </Link>
        </div>
      </div>
    </section>
  );
};

export default OurCollection;