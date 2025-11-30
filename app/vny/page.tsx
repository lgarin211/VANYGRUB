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
      <div className="bg-gray-50 py-4">
        <div className="container mx-auto px-4">
          <div className="flex items-center space-x-2 text-sm">
            <Link href="/" className="text-red-600 hover:text-red-700">
              Vany Group
            </Link>
            <span className="text-gray-400">/</span>
            <span className="text-gray-900 font-medium">VNY Store</span>
          </div>
        </div>
      </div>

      {/* Welcome Section */}
      <section className="bg-gradient-to-r from-red-600 to-red-800 text-white py-16">
        <div className="container mx-auto px-4 text-center">
          <h1 className="text-5xl font-bold mb-6">
            Welcome to VNY Store
          </h1>
          <p className="text-xl mb-8 max-w-3xl mx-auto leading-relaxed">
            Temukan koleksi sepatu dan fashion terbaik untuk gaya hidup modern Anda. 
            VNY Store menghadirkan produk berkualitas tinggi dengan desain yang stylish dan nyaman.
          </p>
          <div className="flex flex-col sm:flex-row justify-center items-center gap-4">
            <Link 
              href="/vny/product" 
              className="bg-white text-red-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-colors shadow-lg"
            >
              🛍️ Belanja Sekarang
            </Link>
            <Link 
              href="/gallery" 
              className="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-red-600 transition-colors"
            >
              🖼️ Lihat Gallery
            </Link>
          </div>
        </div>
      </section>

      {/* Hero Section */}
      <HeroSection />

      {/* Featured Categories */}
      <section className="py-16 bg-gray-50">
        <div className="container mx-auto px-4">
          <div className="text-center mb-12">
            <h2 className="text-4xl font-bold text-gray-900 mb-4">
              Kategori Unggulan
            </h2>
            <p className="text-xl text-gray-600 max-w-2xl mx-auto">
              Jelajahi berbagai kategori produk pilihan dari VNY Store
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {/* Sneakers Category */}
            <div className="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow group">
              <div className="relative h-64 overflow-hidden">
                <img 
                  src="/temp/nike-just-do-it(6).jpg" 
                  alt="Sneakers Collection" 
                  className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div className="absolute bottom-4 left-4 text-white">
                  <h3 className="text-2xl font-bold mb-2">Sneakers</h3>
                  <p className="text-white/90">Koleksi sepatu olahraga terbaru</p>
                </div>
              </div>
              <div className="p-6">
                <Link 
                  href="/vny/product?category=sneakers" 
                  className="inline-flex items-center text-red-600 font-semibold hover:text-red-700 transition-colors"
                >
                  Explore Collection
                  <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
                  </svg>
                </Link>
              </div>
            </div>

            {/* Casual Shoes Category */}
            <div className="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow group">
              <div className="relative h-64 overflow-hidden">
                <img 
                  src="/temp/nike-just-do-it(7).jpg" 
                  alt="Casual Shoes Collection" 
                  className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div className="absolute bottom-4 left-4 text-white">
                  <h3 className="text-2xl font-bold mb-2">Casual</h3>
                  <p className="text-white/90">Sepatu kasual untuk sehari-hari</p>
                </div>
              </div>
              <div className="p-6">
                <Link 
                  href="/vny/product?category=casual" 
                  className="inline-flex items-center text-red-600 font-semibold hover:text-red-700 transition-colors"
                >
                  Explore Collection
                  <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
                  </svg>
                </Link>
              </div>
            </div>

            {/* Fashion Category */}
            <div className="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow group">
              <div className="relative h-64 overflow-hidden">
                <img 
                  src="/temp/nike-just-do-it(8).jpg" 
                  alt="Fashion Collection" 
                  className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div className="absolute bottom-4 left-4 text-white">
                  <h3 className="text-2xl font-bold mb-2">Fashion</h3>
                  <p className="text-white/90">Gaya fashion terkini dan trendy</p>
                </div>
              </div>
              <div className="p-6">
                <Link 
                  href="/vny/product?category=fashion" 
                  className="inline-flex items-center text-red-600 font-semibold hover:text-red-700 transition-colors"
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

      {/* About VNY Section */}
      <section className="py-16 bg-red-50">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto text-center">
            <h2 className="text-4xl font-bold text-gray-900 mb-8">
              Tentang VNY Store
            </h2>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
              <div className="text-left">
                <h3 className="text-2xl font-semibold text-red-600 mb-4">
                  Kualitas & Gaya Terdepan
                </h3>
                <p className="text-gray-700 mb-6 leading-relaxed">
                  VNY Store adalah bagian dari Vany Group yang berkomitmen menghadirkan produk fashion 
                  dan footwear berkualitas tinggi. Dengan pengalaman bertahun-tahun, kami memahami 
                  kebutuhan gaya hidup modern yang dinamis.
                </p>
                <ul className="space-y-2 text-gray-700">
                  <li className="flex items-center">
                    <svg className="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                      <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                    </svg>
                    Produk Original & Bergaransi
                  </li>
                  <li className="flex items-center">
                    <svg className="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                      <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                    </svg>
                    Pengiriman Cepat & Aman
                  </li>
                  <li className="flex items-center">
                    <svg className="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                      <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                    </svg>
                    Customer Service 24/7
                  </li>
                </ul>
              </div>
              <div className="relative">
                <div className="bg-gradient-to-br from-red-500 to-red-700 rounded-2xl p-8 text-white">
                  <h4 className="text-3xl font-bold mb-4">10k+</h4>
                  <p className="text-red-100 mb-6">Pelanggan Puas</p>
                  <div className="grid grid-cols-2 gap-4">
                    <div className="text-center">
                      <div className="text-2xl font-bold">500+</div>
                      <div className="text-red-200 text-sm">Produk</div>
                    </div>
                    <div className="text-center">
                      <div className="text-2xl font-bold">50+</div>
                      <div className="text-red-200 text-sm">Brand</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Call to Action */}
      <section className="py-16 bg-gray-900 text-white">
        <div className="container mx-auto px-4 text-center">
          <h2 className="text-4xl font-bold mb-6">
            Siap Tampil Lebih Stylish?
          </h2>
          <p className="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
            Jangan lewatkan koleksi terbaru dan penawaran menarik dari VNY Store. 
            Bergabunglah dengan ribuan pelanggan yang sudah merasakan kualitas produk kami.
          </p>
          <div className="flex flex-col sm:flex-row justify-center items-center gap-4">
            <Link 
              href="/vny/product" 
              className="bg-red-600 hover:bg-red-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-colors shadow-lg"
            >
              🚀 Mulai Belanja
            </Link>
            <Link 
              href="/vny/cart" 
              className="bg-transparent border-2 border-white text-white hover:bg-white hover:text-gray-900 px-8 py-4 rounded-lg font-semibold text-lg transition-colors"
            >
              🛒 Lihat Keranjang
            </Link>
          </div>
        </div>
      </section>

      {/* Footer */}
      <Footer />
    </div>
  );
};

export default VNYHomePage;