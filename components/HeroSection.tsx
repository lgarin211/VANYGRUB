'use client';

import React, { useEffect, useRef, useState } from 'react';
import Image from 'next/image';
import { gsap } from 'gsap';
import { useHomeData } from '../hooks/useApi';

interface SlideData {
  id: number;
  title: string;
  subtitle: string;
  description: string;
  image: string;
  bgColor: string;
  textColor: string;
  buttonText: string;
  price?: string;
}

const HeroSection: React.FC = () => {
  const sectionRef = useRef<HTMLElement>(null);
  const carouselRef = useRef<HTMLDivElement>(null);
  const textRef = useRef<HTMLDivElement>(null);
  const floatingElementsRef = useRef<HTMLDivElement[]>([]);
  
  const [currentSlide, setCurrentSlide] = useState(0);
  const [isAutoPlay, setIsAutoPlay] = useState(true);

  // Load data from API with fallback
  const { data: homeData, loading, error } = useHomeData();
  const slidesData: SlideData[] = homeData?.heroSection?.slides || [];

  // Auto-play functionality
  useEffect(() => {
    if (!isAutoPlay || loading || !slidesData.length) return;

    const interval = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % slidesData.length);
    }, homeData?.heroSection?.autoPlayInterval || 5000);

    return () => clearInterval(interval);
  }, [isAutoPlay, slidesData.length, loading, homeData]);

  // GSAP Animations for slide transitions
  useEffect(() => {
    const ctx = gsap.context(() => {
      // Slide transition animation is handled by CSS transform
      // Focus on text animation for current slide

      // Text animation for current slide
      if (textRef.current) {
        gsap.fromTo(textRef.current,
          { y: 50, opacity: 0 },
          {
            y: 0,
            opacity: 1,
            duration: 0.8,
            delay: 0.3,
            ease: "back.out(1.7)"
          }
        );
      }

      // Animate floating elements
      floatingElementsRef.current.forEach((el, index) => {
        if (el) {
          // Continuous floating animation
          gsap.to(el, {
            y: -15,
            duration: 2 + Math.random() * 1,
            repeat: -1,
            yoyo: true,
            ease: "sine.inOut",
            delay: index * 0.1
          });

          // Rotation animation
          gsap.to(el, {
            rotation: 360,
            duration: 10 + Math.random() * 5,
            repeat: -1,
            ease: "none",
            delay: index * 0.2
          });

          // Scale pulse
          gsap.to(el, {
            scale: 1.3,
            duration: 1.8 + Math.random() * 0.7,
            repeat: -1,
            yoyo: true,
            ease: "sine.inOut",
            delay: index * 0.3
          });
        }
      });

    }, sectionRef);

    return () => ctx.revert();
  }, [currentSlide]);

  const nextSlide = () => {
    setCurrentSlide((prev) => (prev + 1) % slidesData.length);
  };

  const prevSlide = () => {
    setCurrentSlide((prev) => (prev - 1 + slidesData.length) % slidesData.length);
  };

  const goToSlide = (index: number) => {
    setCurrentSlide(index);
  };



  // Show loading state
  if (loading) {
    return (
      <section className="relative flex items-center justify-center h-screen bg-gradient-to-br from-red-600 to-red-800">
        <div className="text-center text-white">
          <div className="inline-block w-12 h-12 mb-4 border-b-2 border-white rounded-full animate-spin"></div>
          <p className="text-xl">Loading Hero Content...</p>
        </div>
      </section>
    );
  }
  
  // Show fallback if no slides available
  if (!slidesData.length) {
    return (
      <section className="relative flex items-center justify-center h-screen bg-gradient-to-br from-red-600 to-red-800">
        <div className="max-w-4xl px-4 text-center text-white">
          <h1 className="mb-4 text-6xl font-bold">VNY</h1>
          <p className="mb-8 text-2xl">Premium Sneaker Collection</p>
          <button className="px-8 py-4 font-semibold text-red-600 transition-colors bg-white rounded-full hover:bg-gray-100">
            Shop Now
          </button>
        </div>
      </section>
    );
  }

  return (
    <section ref={sectionRef} className="relative min-h-screen overflow-hidden">
      {/* Carousel container */}
      <div className="relative w-full h-screen">
        <div 
          ref={carouselRef}
          className="flex w-full h-full"
          style={{ 
            transform: `translateX(-${currentSlide * 100}%)`,
            transition: 'transform 1s ease-in-out'
          }}
        >
          {slidesData.map((slide, index) => (
            <div 
              key={slide.id}
              className="relative flex-shrink-0 w-full h-full"
              style={{ 
                background: `linear-gradient(135deg, ${slide.bgColor}dd, ${slide.bgColor}aa)` 
              }}
            >
              {/* Single Clean Background Image Overlay */}
              <div className="absolute inset-0 opacity-15">
                <Image
                  src={slide.image}
                  alt="Background sneaker"
                  fill
                  className="object-cover blur-sm"
                  priority={false}
                  sizes="100vw"
                />
              </div>

              {/* Gradient Overlay for better text readability */}
              <div className="absolute inset-0 bg-gradient-to-r from-black/20 via-transparent to-black/20"></div>

              {/* Slide content */}
              <div className="absolute inset-0 z-20 flex items-center justify-between px-8 md:px-16">
                {/* Text content */}
                <div 
                  ref={index === currentSlide ? textRef : null}
                  className="relative z-30 flex-1 max-w-2xl"
                  style={{ color: slide.textColor }}
                >
                  <h1 className="mb-4 font-black tracking-wider text-7xl md:text-9xl drop-shadow-lg">
                    {slide.title}
                  </h1>
                  <h2 className="mb-6 text-3xl font-bold md:text-5xl opacity-90">
                    {slide.subtitle}
                  </h2>
                  <p className="max-w-xl mb-8 text-xl leading-relaxed md:text-2xl opacity-80">
                    {slide.description}
                  </p>
                  <div className="flex items-center gap-6 mb-8">
                    <span className="text-4xl font-bold">{slide.price}</span>
                    <button 
                      className="px-8 py-4 text-lg font-bold text-gray-800 transition-all duration-300 transform rounded-full shadow-xl bg-white/90 hover:bg-white hover:scale-105 backdrop-blur-sm"
                      style={{ 
                        backgroundColor: slide.textColor === '#ffffff' ? 'rgba(255,255,255,0.9)' : 'rgba(0,0,0,0.9)',
                        color: slide.textColor === '#ffffff' ? '#1f2937' : '#ffffff'
                      }}
                    >
                      {slide.buttonText}
                    </button>
                  </div>
                </div>

                {/* Product image */}
                <div className="relative z-30 flex items-center justify-center flex-1">
                  <div className="relative w-[300px] h-[300px] md:w-[500px] md:h-[500px]">
                    {/* Background blur effect behind image */}
                    <div className="absolute inset-0 scale-110 -z-10 blur-3xl opacity-30"
                         style={{ backgroundColor: slide.textColor }}
                    ></div>
                    
                    <Image
                      src={slide.image} 
                      alt={slide.title}
                      fill
                      className="relative z-10 object-contain transition-transform duration-500 transform drop-shadow-2xl hover:scale-105"
                      priority={index === currentSlide}
                      sizes="(max-width: 768px) 300px, 500px"
                      onError={(e) => {
                        console.log('Image failed to load:', slide.image);
                      }}
                    />
                  </div>
                </div>
              </div>



              {/* Enhanced Floating Elements for each slide */}
              <div className="absolute inset-0 pointer-events-none z-5">
                {[...Array(20)].map((_, i) => (
                  <div 
                    key={`${slide.id}-${i}`}
                    ref={el => {
                      if (index === currentSlide && el) {
                        floatingElementsRef.current[i] = el;
                      }
                    }}
                    className={`absolute opacity-20 rounded-full blur-sm`}
                    style={{ 
                      backgroundColor: slide.textColor,
                      width: `${Math.floor(Math.random() * 32) + 16}px`,
                      height: `${Math.floor(Math.random() * 32) + 16}px`,
                      top: `${Math.random() * 100}%`,
                      left: `${Math.random() * 100}%`,
                      transform: `rotate(${Math.random() * 360}deg)`
                    }}
                  ></div>
                ))}
              </div>
            </div>
          ))}
        </div>
      </div>

      {/* Navigation controls */}
      <div className="absolute flex items-center gap-4 transform -translate-x-1/2 bottom-8 left-1/2">
        {/* Previous button */}
        <button 
          onClick={prevSlide}
          className="p-3 transition-all duration-300 border rounded-full bg-white/20 backdrop-blur-sm border-white/30 hover:bg-white/30"
        >
          <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
          </svg>
        </button>

        {/* Slide indicators */}
        <div className="flex gap-2">
          {slidesData.map((_, index) => (
            <button
              key={index}
              onClick={() => goToSlide(index)}
              className={`w-3 h-3 rounded-full transition-all duration-300 ${
                index === currentSlide 
                  ? 'bg-white scale-125' 
                  : 'bg-white/50 hover:bg-white/70'
              }`}
            />
          ))}
        </div>

        {/* Next button */}
        <button 
          onClick={nextSlide}
          className="p-3 transition-all duration-300 border rounded-full bg-white/20 backdrop-blur-sm border-white/30 hover:bg-white/30"
        >
          <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </div>

      {/* Slide counter */}
      <div className="absolute px-4 py-2 text-white rounded-full top-8 right-8 bg-black/30 backdrop-blur-sm">
        <span className="text-lg font-bold">
          {String(currentSlide + 1).padStart(2, '0')} / {String(slidesData.length).padStart(2, '0')}
        </span>
      </div>
    </section>
  );
};

export default HeroSection;