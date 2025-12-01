'use client';

import React, { useEffect } from 'react';
import Header from '../../../components/Header';
import ProductList from '../../../components/ProductList';
import { preloadImages } from '../../../lib/cache';

const ProductPage: React.FC = () => {
  useEffect(() => {
    // Preload product images for better UX
    const productImages = [
      '/temp/nike-just-do-it(1).jpg',
      '/temp/nike-just-do-it(2).jpg',
      '/temp/nike-just-do-it(3).jpg',
      '/temp/nike-just-do-it(4).jpg',
      '/temp/nike-just-do-it(5).jpg',
      '/temp/nike-just-do-it(6).jpg',
      '/temp/nike-just-do-it(7).jpg',
      '/temp/nike-just-do-it(8).jpg'
    ];

    preloadImages(productImages).then(() => {
      console.log('Product page: Images preloaded');
    });
  }, []);

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