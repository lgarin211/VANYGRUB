'use client';

import React from 'react';
import Link from 'next/link';
import Image from 'next/image';
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

  // Filter active categories and sort by sortOrder
  const categories = (apiCategories || [] as Category[])
    .filter((cat: Category) => cat.isActive)
    .sort((a: Category, b: Category) => (a.sortOrder || 0) - (b.sortOrder || 0));

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
              <div className="relative h-48 overflow-hidden md:h-64">
                <Image 
                  src={category.image} 
                  alt={`${category.name} Collection`} 
                  width={400}
                  height={256}
                  className="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110"
                  onError={(e) => {
                    // Fallback to placeholder if image fails to load
                    const target = e.target as HTMLImageElement;
                    target.src = `/temp/nike-just-do-it(${category.id % 3 + 6}).jpg`;
                  }}
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div className="absolute text-white bottom-3 md:bottom-4 left-3 md:left-4">
                  <h3 className="mb-1 text-xl font-bold md:mb-2 md:text-2xl">
                    {category.name}
                  </h3>
                  <p className="text-sm md:text-base text-white/90 capitalize">
                    {category.description}
                  </p>
                </div>
              </div>
              <div className="p-4 md:p-6">
                <Link 
                  href={`/vny/product?category=${category.slug}`}
                  className="inline-flex items-center text-sm font-semibold text-red-600 transition-colors md:text-base hover:text-red-700"
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
      </div>
    </section>
  );
};

export default FeaturedCategories;