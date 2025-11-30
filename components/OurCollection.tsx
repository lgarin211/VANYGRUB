'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import { useHomeData } from '../hooks/useApi';

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

  const itemsPerView = 4; // Show 4 items at once
  const maxIndex = Math.max(0, collections.length - itemsPerView);

  const nextSlide = () => {
    setCurrentIndex((prev) => (prev >= maxIndex ? 0 : prev + 1));
  };

  const prevSlide = () => {
    setCurrentIndex((prev) => (prev <= 0 ? maxIndex : prev - 1));
  };

  return (
    <section className="bg-gray-100 py-16">
      <div className="container mx-auto px-4">
        {/* Section Title */}
        <div className="text-left mb-8 px-8">
          <h2 
            className="text-3xl font-bold text-gray-800"
            data-aos="fade-up"
            data-aos-delay="100"
          >
            {title}
          </h2>
          <p className="text-gray-600 mt-2 text-lg">{subtitle}</p>
        </div>

        {/* Carousel Container */}
        <div className="relative">
          {/* Navigation Buttons */}
          <button 
            onClick={prevSlide}
            className="absolute left-2 top-1/2 -translate-y-1/2 z-30 bg-white/90 hover:bg-white rounded-full p-3 shadow-lg transition-all duration-300 border border-gray-200"
            data-aos="fade-right"
            data-aos-delay="400"
          >
            <svg className="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2">
              <path strokeLinecap="round" strokeLinejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          
          <button 
            onClick={nextSlide}
            className="absolute right-2 top-1/2 -translate-y-1/2 z-30 bg-white/90 hover:bg-white rounded-full p-3 shadow-lg transition-all duration-300 border border-gray-200"
            data-aos="fade-left"
            data-aos-delay="400"
          >
            <svg className="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2">
              <path strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7" />
            </svg>
          </button>

          {/* Collection Carousel - Clean Design */}
          <div className="overflow-hidden px-8">
            <div 
              className="flex gap-5 transition-transform duration-500 ease-out"
              style={{ 
                transform: `translateX(-${currentIndex * 310}px)`
              }}
            >
              {collections.map((item: any, index: number) => (
                <div 
                  key={item.id}
                  className="relative flex-shrink-0 bg-gray-800 rounded-xl cursor-pointer group overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1"
                  style={{ 
                    width: '290px',
                    height: '200px'
                  }}
                  data-aos="zoom-in"
                  data-aos-delay={200 + index * 50}
                >
                  {/* Badge for special items */}
                  {item.badge && (
                    <div className="absolute top-3 right-3 bg-black/80 text-white px-2 py-1 rounded-md text-xs font-medium z-30 backdrop-blur-sm">
                      {item.badge}
                    </div>
                  )}
                  
                  {/* Product Image Container */}
                  <div className="relative w-full h-full overflow-hidden rounded-xl">
                    <Image
                      src={item.image}
                      alt={item.name}
                      fill
                      className="object-cover group-hover:scale-110 transition-transform duration-500"
                      sizes="290px"
                      priority={index < 5}
                    />
                    
                    {/* Dark overlay for better contrast */}
                    <div className="absolute inset-0 bg-black/25 group-hover:bg-black/15 transition-colors duration-300"></div>
                    
                    {/* Gradient overlay for text area */}
                    <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                  </div>
                  
                  {/* Product Name */}
                  <div className="absolute bottom-4 left-4 right-4 z-20">
                    <h3 className="text-white text-xl font-bold tracking-wide">
                      {item.name}
                    </h3>
                    <p className="text-gray-300 text-sm font-medium opacity-80 mt-1">
                      {item.name}
                    </p>
                  </div>
                  
                  {/* Corner accent */}
                  <div className="absolute top-0 left-0 w-12 h-12 bg-gradient-to-br from-white/10 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
              ))}
            </div>
          </div>
        </div>

        {/* Look More Button */}
        <div className="text-center mt-5">
          <Link 
            href="/collection"
            className="inline-flex items-center bg-red-800 text-white px-8 py-4 rounded-full hover:bg-red-700 transition-all duration-300 transform hover:scale-105 shadow-lg"
            data-aos="fade-up"
            data-aos-delay="600"
          >
            Look More
            <svg 
              className="ml-2 w-5 h-5" 
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