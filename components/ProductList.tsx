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
  const [showMobileFilters, setShowMobileFilters] = useState(false);

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
      {/* Mobile Search & Filter Header */}
      <div className="container mx-auto px-4 pt-4 md:pt-8 pb-4">
        <div className="bg-white rounded-lg p-4 md:p-6 mb-4 md:mb-6">
          <div className="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
            <h1 className="text-xl md:text-2xl font-bold text-gray-900">Produk VNY</h1>
            
            {/* Mobile Filter Toggle & Search */}
            <div className="flex items-center space-x-3">
              <button
                onClick={() => setShowMobileFilters(!showMobileFilters)}
                className="md:hidden flex items-center bg-red-600 text-white px-3 py-2 rounded-lg text-sm"
              >
                <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter
              </button>
              <input
                type="text"
                placeholder="Cari produk..."
                className="border border-gray-300 rounded-lg py-2 px-3 md:px-4 flex-1 md:w-64 focus:outline-none focus:ring-2 focus:ring-red-500 text-sm md:text-base"
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
              />
            </div>
          </div>
        </div>
      </div>

      <div className="container mx-auto px-4 pb-8">
        {/* Mobile Filter Overlay */}
        {showMobileFilters && (
          <div className="fixed inset-0 bg-black bg-opacity-50 z-50 md:hidden" onClick={() => setShowMobileFilters(false)}>
            <div className="fixed inset-x-0 bottom-0 bg-white rounded-t-2xl p-6 max-h-[80vh] overflow-y-auto" onClick={(e) => e.stopPropagation()}>
              <div className="flex items-center justify-between mb-6">
                <h2 className="text-lg font-semibold">Filter Produk</h2>
                <button onClick={() => setShowMobileFilters(false)} className="text-gray-500">
                  <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              
              {/* Mobile Filter Content */}
              <div className="space-y-6">
                {/* Category Filter - Mobile */}
                <div>
                  <h3 className="font-semibold text-gray-700 mb-3">Kategori</h3>
                  <select
                    className="w-full bg-red-800 text-white p-3 rounded-lg"
                    value={selectedCategory}
                    onChange={(e) => setSelectedCategory(e.target.value)}
                  >
                    {categories.map((category, index) => (
                      <option key={`category-${index}`} value={category}>{category}</option>
                    ))}
                  </select>
                </div>
                
                {/* Serial Filter - Mobile */}
                <div>
                  <h3 className="font-semibold text-gray-700 mb-3">Serial</h3>
                  <div className="flex flex-wrap gap-2">
                    {serials.map(serial => (
                      <button
                        key={serial}
                        className={`px-4 py-2 text-sm rounded-lg ${
                          selectedSerial === serial
                            ? 'bg-red-800 text-white'
                            : 'bg-gray-200 text-gray-700'
                        }`}
                        onClick={() => setSelectedSerial(serial)}
                      >
                        {serial}
                      </button>
                    ))}
                  </div>
                </div>
                
                {/* Price Filter - Mobile */}
                <div>
                  <h3 className="font-semibold text-gray-700 mb-3">Rentang Harga</h3>
                  <select
                    className="w-full bg-red-800 text-white p-3 rounded-lg"
                    value={selectedPriceRange}
                    onChange={(e) => setSelectedPriceRange(Number(e.target.value))}
                  >
                    {priceRanges.map((range, index) => (
                      <option key={range.label} value={index}>{range.label}</option>
                    ))}
                  </select>
                </div>
                
                <button
                  onClick={() => setShowMobileFilters(false)}
                  className="w-full bg-red-600 text-white py-3 rounded-lg font-semibold mt-6"
                >
                  Terapkan Filter
                </button>
              </div>
            </div>
          </div>
        )}
        
        <div className="flex flex-col md:flex-row">
          {/* Desktop Sidebar Filter */}
          <aside className="hidden md:block w-64 bg-white rounded-lg p-6 h-fit mr-8">
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
            {/* Desktop Additional Search */}
            <div className="hidden md:block mb-6">
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
            
            {/* Results Count & Active Filters - Mobile Friendly */}
            <div className="mb-4 md:mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
              <p className="text-sm md:text-base text-gray-600 mb-2 sm:mb-0">
                Menampilkan {filteredProducts.length} produk
              </p>
              
              {/* Active Filter Pills - Mobile */}
              <div className="md:hidden flex flex-wrap gap-2">
                {selectedCategory !== 'Semua Kategori' && (
                  <span className="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs flex items-center">
                    {selectedCategory}
                    <button onClick={() => setSelectedCategory('Semua Kategori')} className="ml-1">
                      ×
                    </button>
                  </span>
                )}
                {selectedSerial !== 'Semua' && (
                  <span className="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs flex items-center">
                    {selectedSerial}
                    <button onClick={() => setSelectedSerial('Semua')} className="ml-1">
                      ×
                    </button>
                  </span>
                )}
              </div>
            </div>

            {/* Product Grid - Mobile Optimized */}
            <div className="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-6">
              {filteredProducts.map((product: any) => (
                <Link key={product.id} href={`/vny/product/${product.id}`}>
                  <div className="bg-white rounded-lg md:rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer group">
                    <div className="aspect-square bg-gray-200 relative overflow-hidden">
                      <Image
                        src={product.image}
                        alt={product.name}
                        fill
                        className="object-cover group-hover:scale-105 transition-transform duration-300"
                        sizes="(max-width: 640px) 50vw, (max-width: 1024px) 33vw, 25vw"
                      />
                      {!product.inStock && (
                        <div className="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                          <span className="text-white text-xs md:text-sm font-semibold bg-red-600 px-2 py-1 rounded">
                            Stok Habis
                          </span>
                        </div>
                      )}
                    </div>
                    <div className="p-3 md:p-4">
                      <h3 className="font-semibold text-gray-900 mb-1 text-sm md:text-base line-clamp-2">
                        {product.name}
                      </h3>
                      <p className="text-xs md:text-sm text-gray-600 mb-2 line-clamp-1">
                        {product.description}
                      </p>
                      <div className="flex items-center justify-between">
                        <p className="text-sm md:text-lg font-bold text-gray-900">
                          {product.price}
                        </p>
                        {product.featured && (
                          <span className="bg-red-600 text-white text-xs px-2 py-1 rounded-full">
                            Hot
                          </span>
                        )}
                      </div>
                    </div>
                  </div>
                </Link>
              ))}
            </div>

            {/* No Results */}
            {filteredProducts.length === 0 && (
              <div className="text-center py-8 md:py-12 col-span-full">
                <div className="text-gray-400 mb-4">
                  <svg className="w-16 h-16 md:w-24 md:h-24 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.47.711-6.26 1.935l-.349.292a.5.5 0 00.133.842l4.75 2.375a.5.5 0 00.632-.14l1.222-1.454c.211-.252.483-.4.775-.4h.643c.292 0 .564.148.775.4l1.222 1.454a.5.5 0 00.632.14l4.75-2.375a.5.5 0 00.133-.842l-.349-.292z" />
                  </svg>
                </div>
                <p className="text-gray-500 text-base md:text-lg mb-2">Tidak ada produk yang ditemukan</p>
                <p className="text-gray-400 text-sm">Coba ubah filter atau kata kunci pencarian</p>
              </div>
            )}
          </main>
        </div>
      </div>
    </div>
  );
};

export default ProductList;