'use client';

import React, { useState, useEffect, useRef } from 'react';
import { gsap } from 'gsap';

interface LoadingProps {
  isVisible: boolean;
  onComplete: () => void;
}

const Loading: React.FC<LoadingProps> = ({ isVisible, onComplete }) => {
  const [currentLoader, setCurrentLoader] = useState(0);
  const [progress, setProgress] = useState(0);
  const containerRef = useRef<HTMLDivElement>(null);
  const loaderRef = useRef<HTMLDivElement>(null);
  const progressBarRef = useRef<HTMLDivElement>(null);
  const textRef = useRef<HTMLDivElement>(null);

  const loaders = [
    // Loader 1: Spinning Dots
    <div key="spinner" className="flex space-x-2">
      <div className="w-4 h-4 bg-red-600 rounded-full animate-bounce"></div>
      <div className="w-4 h-4 bg-red-600 rounded-full animate-bounce" style={{animationDelay: '0.1s'}}></div>
      <div className="w-4 h-4 bg-red-600 rounded-full animate-bounce" style={{animationDelay: '0.2s'}}></div>
    </div>,

    // Loader 2: Pulse Ring
    <div key="pulse" className="relative">
      <div className="w-16 h-16 border-4 border-red-600 rounded-full animate-ping"></div>
      <div className="absolute top-2 left-2 w-12 h-12 border-4 border-red-800 rounded-full animate-pulse"></div>
    </div>,

    // Loader 3: Rotating Square
    <div key="square" className="w-12 h-12 border-4 border-red-600 border-t-transparent animate-spin rounded-sm"></div>,

    // Loader 4: Wave Animation
    <div key="wave" className="flex items-end space-x-1">
      {[...Array(5)].map((_, i) => (
        <div 
          key={i}
          className="w-2 bg-red-600 animate-pulse rounded-t"
          style={{
            height: '20px',
            animationDelay: `${i * 0.1}s`,
            animationDuration: '1s'
          }}
        ></div>
      ))}
    </div>,

    // Loader 5: VNY Text Animation
    <div key="text" className="text-4xl font-bold text-red-600">
      <span className="inline-block animate-bounce">V</span>
      <span className="inline-block animate-bounce" style={{animationDelay: '0.1s'}}>N</span>
      <span className="inline-block animate-bounce" style={{animationDelay: '0.2s'}}>Y</span>
    </div>
  ];

  useEffect(() => {
    if (!isVisible) return;

    const ctx = gsap.context(() => {
      // Initial entrance animation
      gsap.fromTo(containerRef.current,
        { opacity: 0, scale: 0.8 },
        { 
          opacity: 1, 
          scale: 1, 
          duration: 0.8, 
          ease: "back.out(1.7)" 
        }
      );

      // Animate progress bar
      gsap.to(progressBarRef.current, {
        width: "100%",
        duration: 5,
        ease: "power2.inOut",
        onUpdate: function() {
          const progressValue = Math.round(this.progress() * 100);
          setProgress(progressValue);
        }
      });

    }, containerRef);

    const interval = setInterval(() => {
      setCurrentLoader((prev) => {
        if (prev < loaders.length - 1) {
          // Animate loader change
          if (loaderRef.current) {
            gsap.fromTo(loaderRef.current,
              { 
                opacity: 0, 
                scale: 0.5, 
                rotation: -180 
              },
              { 
                opacity: 1, 
                scale: 1, 
                rotation: 0, 
                duration: 0.6, 
                ease: "back.out(1.7)" 
              }
            );
          }
          return prev + 1;
        } else {
          clearInterval(interval);
          // Exit animation
          gsap.to(containerRef.current, {
            opacity: 0,
            scale: 0.8,
            duration: 0.8,
            ease: "power2.inOut",
            onComplete: onComplete
          });
          return prev;
        }
      });
    }, 1000);

    return () => {
      clearInterval(interval);
      ctx.revert();
    };
  }, [isVisible, onComplete, loaders.length]);

  if (!isVisible) return null;

  return (
    <div ref={containerRef} className="fixed inset-0 bg-white z-50 flex flex-col items-center justify-center">
      {/* Background Pattern */}
      <div className="absolute inset-0 opacity-5">
        <div className="absolute top-10 left-10 w-20 h-20 bg-red-600 rounded-full"></div>
        <div className="absolute top-32 right-16 w-16 h-16 bg-orange-400 rounded-full"></div>
        <div className="absolute bottom-20 left-20 w-12 h-12 bg-green-400 rounded-full"></div>
        <div className="absolute bottom-32 right-32 w-24 h-24 bg-purple-400 rounded-full"></div>
      </div>

      {/* Main Loading Content */}
      <div className="relative z-10 flex flex-col items-center space-y-8">
        {/* Current Loader with GSAP animation */}
        <div ref={loaderRef} key={currentLoader}>
          {loaders[currentLoader]}
        </div>

        {/* Loading Text */}
        <div ref={textRef} className="text-center">
          <h2 className="text-2xl font-bold text-gray-800 mb-2">
            {currentLoader === 0 && "Initializing VNY..."}
            {currentLoader === 1 && "Loading Collections..."}
            {currentLoader === 2 && "Preparing Sneakers..."}
            {currentLoader === 3 && "Almost Ready..."}
            {currentLoader === 4 && " VNY!"}
          </h2>
          <p className="text-gray-600">Please wait while we set things up</p>
        </div>

        {/* Progress Bar */}
        <div className="w-64 bg-gray-200 rounded-full h-2 overflow-hidden">
          <div 
            ref={progressBarRef}
            className="h-full bg-gradient-to-r from-red-600 to-red-800 rounded-full w-0"
          ></div>
        </div>

        {/* Progress Percentage */}
        <div className="text-sm text-gray-500 font-medium">
          {Math.round(progress)}%
        </div>

        {/* Loader Indicators */}
        <div className="flex space-x-2">
          {loaders.map((_, index) => (
            <div 
              key={index}
              className={`w-2 h-2 rounded-full transition-all duration-300 ${
                index <= currentLoader ? 'bg-red-600 scale-125' : 'bg-gray-300'
              }`}
            ></div>
          ))}
        </div>
      </div>

      {/* Loading Stats */}
      <div className="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-center">
        <div className="text-xs text-gray-400">
          Step {currentLoader + 1} of {loaders.length}
        </div>
      </div>
    </div>
  );
};

export default Loading;