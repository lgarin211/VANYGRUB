'use client';

import React from 'react';
import Link from 'next/link';
import Header from '../../components/Header';
import HeroSection from '../../components/HeroSection';
import FeaturedCategories from '../../components/FeaturedCategories';
import ProductGrid from '../../components/ProductGrid';
import OurCollection from '../../components/OurCollection';
import SpecialOffer from '../../components/SpecialOffer';
import Footer from '../../components/Footer';

const VNYHomePage: React.FC = () => {
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