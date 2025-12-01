'use client';

import React, { useEffect } from 'react';
import Link from 'next/link';
import Header from '../../components/Header';
import { preloadImages } from '../../lib/cache';
import HeroSection from '../../components/HeroSection';
import FeaturedCategories from '../../components/FeaturedCategories';
import ProductGrid from '../../components/ProductGrid';
import OurCollection from '../../components/OurCollection';
import SpecialOffer from '../../components/SpecialOffer';
import Footer from '../../components/Footer';

const VNYHomePage: React.FC = () => {
  useEffect(() => {
    // Preload critical images for better performance
    const criticalImages = [
      '/temp/nike-just-do-it(6).jpg',
      '/temp/nike-just-do-it(7).jpg',
      '/temp/nike-just-do-it(8).jpg',
      '/temp/nike-just-do-it(9).jpg',
      '/temp/nike-just-do-it(10).jpg'
    ];

    preloadImages(criticalImages).then(() => {
      console.log('VNY page: Critical images preloaded');
    });
  }, []);

  return (
    <div className="min-h-screen bg-white">
      {/* Header */}
      <Header />

      {/* Breadcrumb */}
      <div className="py-3 md:py-4 bg-gray-50">
        <div className="container px-4 mx-auto">
          <div className="flex items-center space-x-2 text-xs md:text-sm">
            <Link href="/" className="text-red-600 hover:text-red-700">
              Vany Group
            </Link>
            <span className="text-gray-400">/</span>
            <span className="font-medium text-gray-900">VNY Store</span>
          </div>
        </div>
      </div>

      {/* Hero Section */}
      <HeroSection />

      {/* Featured Categories */}
      <FeaturedCategories />

      {/* Product Grid */}
      <ProductGrid />

      {/* Our Collection */}
      <OurCollection />

      {/* Special Offer */}
      <SpecialOffer />
      {/* Footer */}
      <Footer />
    </div>
  );
};

export default VNYHomePage;