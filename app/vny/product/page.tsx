'use client';

import React from 'react';
import Header from '../../../components/Header';
import ProductList from '../../../components/ProductList';

const ProductPage: React.FC = () => {
  return (
    <main className="min-h-screen bg-gray-50">
      <Header />
      
      {/* Mobile-optimized container */}
      <div className="container mx-auto px-4 py-4 md:py-6">
        {/* Page Title - Mobile Responsive */}
        <div className="mb-6 md:mb-8">
          <h1 className="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
            Koleksi Produk
          </h1>
          <p className="text-sm md:text-base text-gray-600">
            Temukan sepatu dan fashion terbaik dari VNY Store
          </p>
        </div>
        
        <ProductList />
      </div>
    </main>
  );
};

export default ProductPage;