'use client';

import React from 'react';
import Link from 'next/link';
import Header from '../../components/Header';
import HeroSection from '../../components/HeroSection';
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
      <div className="py-4 bg-gray-50">
        <div className="container px-4 mx-auto">
          <div className="flex items-center space-x-2 text-sm">
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
      <section className="py-16 bg-gray-50">
        <div className="container px-4 mx-auto">
          <div className="mb-12 text-center">
            <h2 className="mb-4 text-4xl font-bold text-gray-900">
              Kategori Unggulan
            </h2>
            <p className="max-w-2xl mx-auto text-xl text-gray-600">
              Jelajahi berbagai kategori produk pilihan dari VNY Store
            </p>
          </div>

          <div className="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            {/* Sneakers Category */}
            <div className="overflow-hidden transition-shadow bg-white shadow-lg rounded-2xl hover:shadow-xl group">
              <div className="relative h-64 overflow-hidden">
                <img 
                  src="/temp/nike-just-do-it(6).jpg" 
                  alt="Sneakers Collection" 
                  className="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div className="absolute text-white bottom-4 left-4">
                  <h3 className="mb-2 text-2xl font-bold">Sneakers</h3>
                  <p className="text-white/90">Koleksi sepatu olahraga terbaru</p>
                </div>
              </div>
              <div className="p-6">
                <Link 
                  href="/vny/product?category=sneakers" 
                  className="inline-flex items-center font-semibold text-red-600 transition-colors hover:text-red-700"
                >
                  Explore Collection
                  <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
                  </svg>
                </Link>
              </div>
            </div>

            {/* Casual Shoes Category */}
            <div className="overflow-hidden transition-shadow bg-white shadow-lg rounded-2xl hover:shadow-xl group">
              <div className="relative h-64 overflow-hidden">
                <img 
                  src="/temp/nike-just-do-it(7).jpg" 
                  alt="Casual Shoes Collection" 
                  className="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div className="absolute text-white bottom-4 left-4">
                  <h3 className="mb-2 text-2xl font-bold">Casual</h3>
                  <p className="text-white/90">Sepatu kasual untuk sehari-hari</p>
                </div>
              </div>
              <div className="p-6">
                <Link 
                  href="/vny/product?category=casual" 
                  className="inline-flex items-center font-semibold text-red-600 transition-colors hover:text-red-700"
                >
                  Explore Collection
                  <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
                  </svg>
                </Link>
              </div>
            </div>

            {/* Fashion Category */}
            <div className="overflow-hidden transition-shadow bg-white shadow-lg rounded-2xl hover:shadow-xl group">
              <div className="relative h-64 overflow-hidden">
                <img 
                  src="/temp/nike-just-do-it(8).jpg" 
                  alt="Fashion Collection" 
                  className="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div className="absolute text-white bottom-4 left-4">
                  <h3 className="mb-2 text-2xl font-bold">Fashion</h3>
                  <p className="text-white/90">Gaya fashion terkini dan trendy</p>
                </div>
              </div>
              <div className="p-6">
                <Link 
                  href="/vny/product?category=fashion" 
                  className="inline-flex items-center font-semibold text-red-600 transition-colors hover:text-red-700"
                >
                  Explore Collection
                  <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
                  </svg>
                </Link>
              </div>
            </div>
          </div>
        </div>
      </section>

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