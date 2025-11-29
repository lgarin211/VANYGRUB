'use client';

import React, { useState } from 'react';
import Image from 'next/image';
import Link from 'next/link';
import { useParams } from 'next/navigation';
import productsData from '../../../constants/productsData.json';

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

const ProductDetail: React.FC = () => {
  const params = useParams();
  const productId = Number(params.id);
  const [quantity, setQuantity] = useState(1);
  const [showingMainImage, setShowingMainImage] = useState(true);
  const [activeView, setActiveView] = useState<'main' | '3d' | 'thumb1' | 'thumb2' | 'thumb3'>('main');

  // Find product by ID
  const product: Product | undefined = productsData.products.find(p => p.id === productId);

  // 3D Model URLs untuk berbagai produk
  const get3DModelUrl = (productId: number) => {
    const modelUrls = [
      "https://sketchfab.com/models/9425d7ed2fee4d7582c48d57f1eeb93a/embed?autostart=1&dnt=1",
      "https://sketchfab.com/models/90ea1a863ea547fe80da67db2ef835dc/embed",
      "https://sketchfab.com/models/3387ec269ff64f6a847752c88164f8bf/embed",
      "https://sketchfab.com/models/311cc9511f25496cbd7af392d14fd89f/embed",
      "https://sketchfab.com/models/7ce93245f9d8440180d25d1c6b566fa0/embed",
      "https://sketchfab.com/models/013b775a5ad5441ebadb316c98902de3/embed",
      "https://sketchfab.com/models/3f871b7a3a09445fb8f93c7a7e210616/embed"
    ];
    
    return modelUrls[productId % modelUrls.length] || modelUrls[0];
  };

  if (!product) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="text-center">
          <h1 className="text-2xl font-bold text-gray-900 mb-4">Produk tidak ditemukan</h1>
          <Link href="/product" className="text-red-600 hover:text-red-800">
            ← Kembali ke Produk
          </Link>
        </div>
      </div>
    );
  }

  const handleAddToCart = () => {
    // TODO: Implement add to cart functionality
    alert(`Menambahkan ${quantity} ${product.name} ke keranjang`);
  };

  return (
    <div className="bg-gray-100 min-h-screen">
      {/* Header */}
      <header className="bg-red-800 text-white py-4">
        <div className="container mx-auto px-4 flex justify-between items-center">
          {/* Search Bar */}
          <div className="flex items-center">
            <div className="mr-8">
              <input
                type="text"
                placeholder="Q Search"
                className="bg-transparent border-b border-white text-white placeholder-white/70 py-1 px-2 focus:outline-none focus:border-white/100"
              />
            </div>
          </div>

          {/* Logo */}
          <Link href="/" className="text-2xl font-bold">
            VNY
          </Link>

          {/* Navigation */}
          <div className="flex space-x-6">
            <button className="hover:text-gray-300">CART</button>
            <button className="hover:text-gray-300">TRANSACTION</button>
          </div>
        </div>

        {/* Navigation Menu */}
        <nav className="container mx-auto px-4 mt-4">
          <div className="flex space-x-8">
            <Link href="/" className="hover:text-gray-300">HOME</Link>
            <Link href="/product" className="border-b-2 border-white pb-1">PRODUCT</Link>
            <Link href="/about" className="hover:text-gray-300">ABOUT VNY</Link>
            <Link href="/gallery" className="hover:text-gray-300">GALLERY</Link>
          </div>
        </nav>
      </header>

      <div className="container mx-auto px-4 py-8">
        {/* Breadcrumb */}
        <div className="mb-6">
          <Link href="/product" className="text-red-600 hover:text-red-800 flex items-center">
            ← Kembali ke Produk
          </Link>
        </div>

        <div className="bg-white rounded-lg p-6 lg:p-8">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            {/* Product Images */}
            <div className="space-y-4">
              {/* Main Image/3D Viewer */}
              <div className="aspect-square bg-gradient-to-br from-gray-700 via-gray-600 to-gray-800 rounded-2xl overflow-hidden shadow-lg relative">
                {activeView === '3d' ? (
                  // 3D Sketchfab Viewer
                  <iframe
                    title="3D Product Model"
                    frameBorder="0"
                    allowFullScreen
                    allow="autoplay; fullscreen; xr-spatial-tracking"
                    className="w-full h-full"
                    src={get3DModelUrl(productId)}
                  />
                ) : activeView === 'main' ? (
                  // Main Product Image
                  <>
                    <Image
                      src={product.image}
                      alt={product.name}
                      width={600}
                      height={600}
                      className="w-full h-full object-contain p-8"
                    />
                    {/* Brand watermark */}
                    <div className="absolute bottom-6 left-6 text-white/60 text-2xl font-light tracking-wider">
                      {product.name.split(' ')[0]}
                    </div>
                  </>
                ) : (
                  // Thumbnail Views
                  <Image
                    src={product.image}
                    alt={`${product.name} view`}
                    width={600}
                    height={600}
                    className="w-full h-full object-contain p-8"
                  />
                )}
              </div>
              
              {/* Thumbnail Images with 3D Button */}
              <div className="grid grid-cols-4 gap-3">
                {/* Main Image Thumbnail */}
                <div 
                  onClick={() => setActiveView('main')}
                  className={`aspect-square bg-gray-200 rounded-lg overflow-hidden cursor-pointer hover:opacity-75 transition-all border-2 ${
                    activeView === 'main' ? 'border-red-500' : 'border-transparent hover:border-red-300'
                  }`}
                >
                  <Image
                    src={product.image}
                    alt={product.name}
                    width={150}
                    height={150}
                    className="w-full h-full object-cover"
                  />
                </div>
                
                {/* 3D View Button */}
                <div 
                  onClick={() => setActiveView('3d')}
                  className={`aspect-square bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg cursor-pointer hover:opacity-75 transition-all border-2 flex items-center justify-center ${
                    activeView === '3d' ? 'border-red-500' : 'border-transparent hover:border-red-300'
                  }`}
                >
                  <div className="text-center">
                    <div className="text-white text-2xl mb-1">🌐</div>
                    <div className="text-white text-xs font-semibold">3D View</div>
                  </div>
                </div>

                {/* Additional Thumbnails */}
                {[1, 2].map((index) => (
                  <div 
                    key={index}
                    onClick={() => setActiveView(`thumb${index}` as any)}
                    className={`aspect-square bg-gray-200 rounded-lg overflow-hidden cursor-pointer hover:opacity-75 transition-all border-2 ${
                      activeView === `thumb${index}` ? 'border-red-500' : 'border-transparent hover:border-red-300'
                    }`}
                  >
                    <Image
                      src={product.image}
                      alt={`${product.name} ${index + 1}`}
                      width={150}
                      height={150}
                      className="w-full h-full object-cover"
                    />
                  </div>
                ))}
              </div>

              {/* 3D Info Badge */}
              {activeView === '3d' && (
                <div className="bg-blue-50 border border-blue-200 rounded-lg p-3">
                  <div className="flex items-center">
                    <div className="text-blue-500 text-lg mr-2">ℹ️</div>
                    <div className="text-sm text-blue-700">
                      <strong>3D Interactive Model</strong> - Rotate, zoom, and explore the product in 360°
                    </div>
                  </div>
                </div>
              )}
            </div>

            {/* Product Info */}
            <div className="space-y-4">
              {/* Category */}
              <div className="text-sm text-gray-600">
                Jenis sepatu
              </div>

              {/* Product Name */}
              <h1 className="text-3xl font-bold text-gray-900">
                {product.name}
              </h1>

              {/* Tags */}
              <div className="flex flex-wrap gap-2">
                <span className="bg-red-100 text-red-800 px-4 py-2 rounded-full text-sm font-medium">
                  Release Date 28/11/2025
                </span>
                <span className="bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm font-medium">
                  Trending
                </span>
                <span className="bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm font-medium">
                  Belanja
                </span>
              </div>

              {/* Price */}
              <div className="text-4xl font-bold text-gray-900">
                {product.price}
              </div>

              {/* Description */}
              <p className="text-gray-600 leading-relaxed">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc egestas neque id elementum 
                pulvinar. Nullam pharetra magna quis eros dictum, in blandit eros pulvinar. Donec iaculis 
                maximus tellus, at commodo mauris fermentum at. Suspendisse vehicula, leo nec efficitur 
                scelerisque, tellus ex aliquet eros, quis ullamcorper tortor nunc nec dolor.
              </p>

              {/* Stock Status */}
              {product.inStock && (
                <div className="flex items-center text-green-600 font-medium">
                  <div className="w-3 h-3 bg-green-600 rounded-full mr-3"></div>
                  Tersedia 3 pcs
                </div>
              )}

              {/* Quantity Selector */}
              <div className="space-y-3">
                <label className="text-sm font-medium text-gray-700">
                  Jumlah:
                </label>
                <div className="flex items-center space-x-4">
                  <button
                    onClick={() => setQuantity(Math.max(1, quantity - 1))}
                    className="w-10 h-10 flex items-center justify-center border-2 border-gray-300 rounded-md hover:bg-gray-50 hover:border-gray-400 transition-colors text-lg font-medium"
                  >
                    −
                  </button>
                  <div className="w-16 h-10 flex items-center justify-center border-2 border-gray-300 rounded-md bg-white text-center font-medium">
                    {quantity}
                  </div>
                  <button
                    onClick={() => setQuantity(quantity + 1)}
                    className="w-10 h-10 flex items-center justify-center border-2 border-gray-300 rounded-md hover:bg-gray-50 hover:border-gray-400 transition-colors text-lg font-medium"
                  >
                    +
                  </button>
                </div>
              </div>

              {/* Action Buttons */}
              <div className="flex flex-col sm:flex-row gap-3 mt-6">
                <button className="flex-1 border-2 border-red-600 text-red-600 py-3 px-6 rounded-md hover:bg-red-50 transition-colors font-medium text-center">
                  Tambah ke Keranjang ( )
                </button>
                <button
                  onClick={handleAddToCart}
                  className="flex-1 bg-red-600 text-white py-3 px-6 rounded-md hover:bg-red-700 transition-colors font-medium disabled:bg-gray-400 disabled:cursor-not-allowed"
                  disabled={!product.inStock}
                >
                  {product.inStock ? 'Beli Langsung' : 'Out of Stock'}
                </button>
              </div>
            </div>
          </div>

          {/* Product Detail Section */}
          <div className="mt-16 pt-8 border-t border-gray-200">
            <h2 className="text-2xl font-bold text-gray-900 mb-6">Detail Produk</h2>
            <div className="prose max-w-none text-gray-600">
              <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc egestas neque id elementum pulvinar. 
                Nullam pharetra magna quis eros dictum, in blandit eros pulvinar. Donec iaculis maximus tellus, at 
                commodo mauris fermentum at. Suspendisse vehicula, leo nec efficitur scelerisque, tellus ex aliquet 
                eros, quis ullamcorper tortor nunc nec dolor. Sed vel ultrices neque. Quisque pharetra, justo fringilla 
                ultrices condimentum, ipsum dolor gravida ante, tempor accumsan nulla turpis at lectus. Sed gravida 
                mauris at libero tincidunt, et lobortis purus dapibus. Aenean quis metus sit amet est iaculis laoreet.
              </p>
            </div>
          </div>
        </div>
      </div>

      {/* Footer */}
      <footer className="bg-red-800 text-white py-12 mt-16">
        <div className="container mx-auto px-4">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {/* Brand */}
            <div>
              <h3 className="text-2xl font-bold mb-4">VNY</h3>
              <p className="text-sm text-white/80 mb-2">+62 123 456 789</p>
              <p className="text-sm text-white/80">VNY@gmail.com</p>
              
              <div className="flex space-x-4 mt-4">
                <div className="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center cursor-pointer hover:bg-white/30">
                  <span className="text-sm">f</span>
                </div>
                <div className="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center cursor-pointer hover:bg-white/30">
                  <span className="text-sm">t</span>
                </div>
              </div>
            </div>

            {/* Quick Action */}
            <div>
              <h4 className="font-semibold mb-4">Quick Action</h4>
              <div className="space-y-2 text-sm text-white/80">
                <div>Product</div>
                <div>Gallery</div>
              </div>
            </div>

            <div>
              <div className="space-y-2 text-sm text-white/80">
                <div>Our Group</div>
                <div>Contact VNY</div>
              </div>
            </div>
          </div>

          <div className="border-t border-white/20 mt-8 pt-8 text-center text-sm text-white/60">
            © 2025 VNY. All rights reserved
          </div>
        </div>
      </footer>
    </div>
  );
};

export default ProductDetail;