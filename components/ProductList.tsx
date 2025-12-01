'use client';

import React, { useState, useMemo } from 'react';
import Image from 'next/image';
import Link from 'next/link';
import { useProducts, useCategories } from '../hooks/useApi';

interface Product {
  id: number;
  name: string;
  category: string;
  price: string;
  originalPrice: number;
  image: string;
  description: string;
  serial: string;
  inStock: boolean;
  featured: boolean;
}

const ProductList: React.FC = () => {
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedCategory, setSelectedCategory] = useState('Semua Kategori');
  const [selectedSerial, setSelectedSerial] = useState('Semua');
  const [selectedPriceRange, setSelectedPriceRange] = useState(0);

  // Load data from API with fallback
  const { products, loading: productsLoading } = useProducts({ search: searchTerm });
  const { categories: rawCategories, loading: categoriesLoading } = useCategories();
  
  const isLoading = productsLoading || categoriesLoading;
  
  // Transform categories data
  const categories = useMemo(() => {
    const categoryNames = ['Semua Kategori'];
    if (Array.isArray(rawCategories)) {
      rawCategories.forEach((cat: any) => {
        if (typeof cat === 'object' && cat.name) {
          categoryNames.push(cat.name);
        } else if (typeof cat === 'string') {
          categoryNames.push(cat);
        }
      });
    }
    return categoryNames;
  }, [rawCategories]);
  
  // Generate serials and price ranges from products
  const serials = useMemo(() => {
    const uniqueSerials = ['Semua', ...Array.from(new Set(products.map((p: Product) => p.serial)))];
    return uniqueSerials;
  }, [products]);
  
  const priceRanges = [
    { label: 'Semua Harga', min: 0, max: Infinity },
    { label: 'Di bawah 1 Juta', min: 0, max: 1000000 },
    { label: '1-3 Juta', min: 1000000, max: 3000000 },
    { label: '3-5 Juta', min: 3000000, max: 5000000 },
    { label: 'Di atas 5 Juta', min: 5000000, max: Infinity }
  ];

  // Filter products
  const filteredProducts = products.filter((product: Product) => {
    const matchesSearch = product.name.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesCategory = selectedCategory === 'Semua Kategori' || product.category === selectedCategory;
    const matchesSerial = selectedSerial === 'Semua' || product.serial === selectedSerial;
    
    const selectedRange = priceRanges[selectedPriceRange];
    const matchesPrice = product.originalPrice >= selectedRange.min && product.originalPrice <= selectedRange.max;
    
    return matchesSearch && matchesCategory && matchesSerial && matchesPrice;
  });

  if (isLoading) {
    return (
      <div className="bg-gray-100 min-h-screen flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-red-800 mx-auto"></div>
          <p className="mt-4 text-gray-600">Loading products...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="bg-gray-100 min-h-screen">
      {/* Search Section */}
      <div className="container mx-auto px-4 pt-8 pb-4">
        <div className="bg-white rounded-lg p-6 mb-6">
          <div className="flex items-center justify-between">
            <h1 className="text-2xl font-bold text-gray-900">Produk VNY</h1>
            <div className="flex items-center">
              <input
                type="text"
                placeholder="Cari produk..."
                className="border border-gray-300 rounded-lg py-2 px-4 w-64 focus:outline-none focus:ring-2 focus:ring-red-500"
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
              />
            </div>
          </div>
        </div>
      </div>

      <div className="container mx-auto px-4 pb-8 flex">
        {/* Sidebar Filter */}
        <aside className="w-64 bg-white rounded-lg p-6 h-fit mr-8">
          {/* Filter Header */}
          <div className="flex items-center mb-6">
            <div className="w-4 h-4 bg-red-600 rounded mr-2"></div>
            <h2 className="text-lg font-semibold">Filter</h2>
          </div>

          {/* Category Filter */}
          <div className="mb-6">
            <h3 className="font-semibold text-gray-700 mb-3">Jenis</h3>
            <select
              className="w-full bg-red-800 text-white p-2 rounded"
              value={selectedCategory}
              onChange={(e) => setSelectedCategory(e.target.value)}
            >
              {categories.map((category, index) => (
                <option key={`category-${index}`} value={category}>{category}</option>
              ))}
            </select>

            <div className="mt-3 space-y-2">
              <div className="text-sm text-gray-600">Slip On</div>
              <div className="text-sm text-gray-600">Sneakers</div>
              <div className="text-sm text-gray-600">Low Top</div>
            </div>
          </div>

          {/* Serial Filter */}
          <div className="mb-6">
            <h3 className="font-semibold text-gray-700 mb-3">Serial</h3>
            <div className="flex flex-wrap gap-2">
              {serials.map(serial => (
                <button
                  key={serial}
                  className={`px-3 py-1 text-sm rounded ${
                    selectedSerial === serial
                      ? 'bg-red-800 text-white'
                      : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                  }`}
                  onClick={() => setSelectedSerial(serial)}
                >
                  {serial}
                </button>
              ))}
            </div>
          </div>

          {/* Price Filter */}
          <div className="mb-6">
            <h3 className="font-semibold text-gray-700 mb-3">Harga</h3>
            <select
              className="w-full bg-red-800 text-white p-2 rounded mb-3"
              value={selectedPriceRange}
              onChange={(e) => setSelectedPriceRange(Number(e.target.value))}
            >
              {priceRanges.map((range, index) => (
                <option key={range.label} value={index}>{range.label}</option>
              ))}
            </select>

            <div className="space-y-2 text-sm text-gray-600">
              {priceRanges.slice(1).map(range => (
                <div key={range.label}>{range.label}</div>
              ))}
            </div>
          </div>
        </aside>

        {/* Main Content */}
        <main className="flex-1">
          {/* Search Results Header */}
          <div className="mb-6">
            <div className="relative">
              <input
                type="text"
                placeholder="Find Your Shoes"
                className="w-full bg-white rounded-lg p-3 pl-10 border border-gray-300 focus:outline-none focus:border-red-500"
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
              />
              <div className="absolute left-3 top-3.5">
                <svg className="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
            </div>
          </div>

          {/* Product Grid */}
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {filteredProducts.map((product: any) => (
              <Link key={product.id} href={`/vny/product/${product.id}`}>
                <div className="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow cursor-pointer">
                  <div className="aspect-square bg-gray-200 relative">
                    <Image
                      src={product.image}
                      alt={product.name}
                      fill
                      className="object-cover hover:scale-105 transition-transform duration-300"
                      sizes="(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 25vw"
                    />
                  </div>
                  <div className="p-4">
                    <h3 className="font-semibold text-gray-900 mb-1">{product.name}</h3>
                    <p className="text-sm text-gray-600 mb-2">{product.description}</p>
                    <p className="text-lg font-bold text-gray-900">{product.price}</p>
                    {!product.inStock && (
                      <p className="text-sm text-red-500 mt-1">Out of Stock</p>
                    )}
                  </div>
                </div>
              </Link>
            ))}
          </div>

          {/* No Results */}
          {filteredProducts.length === 0 && (
            <div className="text-center py-12">
              <p className="text-gray-500 text-lg">Tidak ada produk yang ditemukan</p>
            </div>
          )}
        </main>
      </div>
    </div>
  );
};

export default ProductList;