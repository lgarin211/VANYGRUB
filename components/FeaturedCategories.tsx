'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import SafeImage from './SafeImage';
import { useCategories } from '../hooks/useApi';

interface Category {
  id: number;
  name: string;
  slug: string;
  description: string;
  image: string;
  isActive: boolean;
  sortOrder?: number;
  productsCount?: number;
}

const FeaturedCategories: React.FC = () => {
  const { data: apiCategories, loading, error } = useCategories();
  const [selectedCategory, setSelectedCategory] = useState<Category | null>(null);
  const [isModalOpen, setIsModalOpen] = useState(false);

  // Filter active categories and sort by sortOrder
  const categories = (apiCategories || [] as Category[])
    .filter((cat: Category) => cat.isActive)
    .sort((a: Category, b: Category) => (a.sortOrder || 0) - (b.sortOrder || 0));

  const openCategoryModal = (category: Category) => {
    setSelectedCategory(category);
    setIsModalOpen(true);
  };

  const closeCategoryModal = () => {
    setIsModalOpen(false);
    setSelectedCategory(null);
  };

  if (loading) {
    return (
      <section className="py-8 md:py-16 bg-gray-50">
        <div className="container px-4 mx-auto">
          <div className="mb-8 text-center md:mb-12">
            <h2 className="mb-3 text-2xl font-bold text-gray-900 md:mb-4 md:text-4xl">
              Kategori Unggulan
            </h2>
            <p className="max-w-2xl px-4 mx-auto text-base text-gray-600 md:text-xl">
              Jelajahi berbagai kategori produk pilihan dari VNY Store
            </p>
          </div>
          
          {/* Loading State */}
          <div className="grid grid-cols-1 gap-6 md:gap-8 md:grid-cols-2 lg:grid-cols-3">
            {[1, 2, 3].map((i) => (
              <div key={i} className="overflow-hidden bg-white shadow-lg rounded-xl md:rounded-2xl animate-pulse">
                <div className="relative h-48 bg-gray-300 md:h-64"></div>
                <div className="p-4 md:p-6">
                  <div className="w-32 h-4 mb-2 bg-gray-300 rounded"></div>
                  <div className="w-24 h-3 bg-gray-300 rounded"></div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>
    );
  }

  if (error || !categories.length) {
    return (
      <section className="py-8 md:py-16 bg-gray-50">
        <div className="container px-4 mx-auto">
          <div className="mb-8 text-center md:mb-12">
            <h2 className="mb-3 text-2xl font-bold text-gray-900 md:mb-4 md:text-4xl">
              Kategori Unggulan
            </h2>
            <p className="max-w-2xl px-4 mx-auto text-base text-gray-600 md:text-xl">
              Jelajahi berbagai kategori produk pilihan dari VNY Store
            </p>
          </div>
          
          <div className="py-16 text-center">
            <p className="text-gray-500">Kategori tidak dapat dimuat saat ini.</p>
          </div>
        </div>
      </section>
    );
  }

  return (
    <section className="py-8 md:py-16 bg-gray-50">
      <div className="container px-4 mx-auto">
        <div className="mb-8 text-center md:mb-12">
          <h2 className="mb-3 text-2xl font-bold text-gray-900 md:mb-4 md:text-4xl">
            Kategori Unggulan
          </h2>
          <p className="max-w-2xl px-4 mx-auto text-base text-gray-600 md:text-xl">
            Jelajahi berbagai kategori produk pilihan dari VNY Store
          </p>
        </div>

        <div className="grid grid-cols-1 gap-6 md:gap-8 md:grid-cols-2 lg:grid-cols-3">
          {categories.map((category: Category) => (
            <div 
              key={category.id}
              className="overflow-hidden transition-shadow bg-white shadow-lg rounded-xl md:rounded-2xl hover:shadow-xl group"
            >
              <div 
                className="relative h-48 overflow-hidden cursor-pointer md:h-64"
                onClick={() => openCategoryModal(category)}
              >
                <SafeImage 
                  src={category.image} 
                  alt={`${category.name} Collection`} 
                  width={400}
                  height={256}
                  className="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div className="absolute text-white bottom-3 md:bottom-4 left-3 md:left-4">
                  <h3 className="mb-1 text-xl font-bold md:mb-2 md:text-2xl">
                    {category.name}
                  </h3>
                </div>
                {/* Click indicator */}
                <div className="absolute transition-opacity opacity-0 top-3 right-3 group-hover:opacity-100">
                  <div className="flex items-center justify-center w-8 h-8 bg-white rounded-full bg-opacity-20 backdrop-blur-sm">
                    <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </div>
                </div>
              </div>
              <div className="p-4 md:p-6">
                <Link 
                  href={`/vny/product?category=${category.slug}`}
                  className="inline-flex items-center text-sm font-semibold text-red-600 transition-colors md:text-base hover:text-red-700"
                  onClick={(e) => e.stopPropagation()}
                >
                  Explore Collection
                  <svg className="w-4 h-4 ml-2 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
                  </svg>
                </Link>
              </div>
            </div>
          ))}
        </div>

        {/* Category Detail Modal */}
        {isModalOpen && selectedCategory && (
          <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
            <div className="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-white rounded-2xl shadow-2xl">
              {/* Close Button */}
              <button
                onClick={closeCategoryModal}
                className="absolute z-10 flex items-center justify-center w-10 h-10 text-white transition-colors bg-black rounded-full top-4 right-4 bg-opacity-30 hover:bg-opacity-50"
              >
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>

              {/* Modal Content */}
              <div className="relative">
                {/* Hero Image */}
                <div className="relative h-64 overflow-hidden md:h-80 rounded-t-2xl">
                  <SafeImage 
                    src={selectedCategory.image} 
                    alt={`${selectedCategory.name} Collection`} 
                    fill
                    className="object-cover"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                </div>

                {/* Modal Body */}
                <div className="p-6 md:p-8">
                  <div className="space-y-6">
                    {/* Category Title */}
                    <div className="text-center">
                      <h2 className="mb-3 text-3xl font-bold text-gray-900 md:text-4xl">
                        {selectedCategory.name}
                      </h2>
                      {selectedCategory.description && (
                        <p className="text-lg text-gray-600 capitalize">
                          {selectedCategory.description}
                        </p>
                      )}
                    </div>

                    {/* Category Description */}
                    <div>
                      <h3 className="mb-3 text-xl font-semibold text-gray-900">
                        Tentang Kategori Ini
                      </h3>
                      <p className="text-gray-600 leading-relaxed">
                        Temukan koleksi {selectedCategory.name.toLowerCase()} terbaik dari VNY Store. 
                        Setiap produk dipilih dengan cermat untuk memberikan kualitas dan style terbaik untuk Anda.
                        {selectedCategory.productsCount && (
                          <span className="block mt-2 font-medium text-red-600">
                            {selectedCategory.productsCount} produk tersedia
                          </span>
                        )}
                      </p>
                    </div>

                    {/* Action Buttons */}
                    <div className="flex flex-col gap-3 sm:flex-row">
                      <Link 
                        href={`/vny/product?category=${selectedCategory.slug}`}
                        className="flex-1 px-6 py-3 font-semibold text-center text-white transition-colors bg-red-600 rounded-lg hover:bg-red-700"
                        onClick={closeCategoryModal}
                      >
                        Jelajahi Produk
                      </Link>
                      <button
                        onClick={closeCategoryModal}
                        className="px-6 py-3 font-semibold text-gray-600 transition-colors bg-gray-100 rounded-lg hover:bg-gray-200"
                      >
                        Tutup
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        )}
      </div>
    </section>
  );
};

export default FeaturedCategories;