@extends('layouts.app')

@section('title', 'Sitemap - VNY Store')

@section('description', 'Complete sitemap of VNY Store website including all product pages, categories, and important sections.')

@section('content')
<!-- Header -->
@include('components.vny-navbar', ['currentPage' => 'sitemap'])

<div class="min-h-screen bg-gray-100">
    <!-- Page Header -->
    <div class="py-12 text-white bg-gradient-to-r from-red-600 to-red-800">
        <div class="container px-4 mx-auto">
            <div class="text-center">
                <h1 class="mb-4 text-4xl font-bold">Sitemap</h1>
                <p class="text-xl text-red-100">Navigasi lengkap website VNY Store</p>
            </div>
        </div>
    </div>

    <!-- Sitemap Content -->
    <div class="container px-4 py-12 mx-auto">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            
            <!-- Main Pages -->
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h2 class="mb-6 text-2xl font-bold text-gray-900">📋 Halaman Utama</h2>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('home') }}" class="flex items-center text-red-600 hover:text-red-800 hover:underline">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 7 5-5 5 5"></path>
                            </svg>
                            Homepage
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('vny.store') }}" class="flex items-center text-red-600 hover:text-red-800 hover:underline">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            VNY Store
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('gallery') }}" class="flex items-center text-red-600 hover:text-red-800 hover:underline">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Gallery
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="flex items-center text-red-600 hover:text-red-800 hover:underline">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            About VNY
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Product Pages -->
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h2 class="mb-6 text-2xl font-bold text-gray-900">👟 Produk & Koleksi</h2>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('vny.product') }}" class="flex items-center text-red-600 hover:text-red-800 hover:underline">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Semua Produk
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('vny.product', ['category' => 'sneakers']) }}" class="flex items-center text-gray-600 hover:text-red-600 hover:underline">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            Kategori Sneakers
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('vny.product', ['category' => 'casual']) }}" class="flex items-center text-gray-600 hover:text-red-600 hover:underline">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            Kategori Casual
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('vny.product', ['category' => 'traditional']) }}" class="flex items-center text-gray-600 hover:text-red-600 hover:underline">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            Kategori Traditional
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Account & Services -->
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h2 class="mb-6 text-2xl font-bold text-gray-900">👤 Akun & Layanan</h2>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('vny.cart') }}" class="flex items-center text-red-600 hover:text-red-800 hover:underline">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                            </svg>
                            Keranjang Belanja
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('transactions') }}" class="flex items-center text-red-600 hover:text-red-800 hover:underline">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Riwayat Transaksi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('customer.review') }}" class="flex items-center text-red-600 hover:text-red-800 hover:underline">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                            Customer Review
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Technical Pages -->
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h2 class="mb-6 text-2xl font-bold text-gray-900">🔧 Teknis & SEO</h2>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('sitemap') }}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-800 hover:underline">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Sitemap XML
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('robots') }}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-800 hover:underline">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Robots.txt
                        </a>
                    </li>
                    <li>
                        <a href="/api/vny/products" target="_blank" class="flex items-center text-green-600 hover:text-green-800 hover:underline">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                            API Products
                        </a>
                    </li>
                    <li>
                        <a href="/api/vny/categories" target="_blank" class="flex items-center text-green-600 hover:text-green-800 hover:underline">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                            API Categories
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- Popular Products Section -->
        <div class="p-6 mt-8 bg-white rounded-lg shadow-md">
            <h2 class="mb-6 text-2xl font-bold text-gray-900">🔥 Produk Populer</h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                <a href="{{ route('product.detail', 1) }}" class="block p-4 transition-all duration-200 border border-gray-200 rounded-lg hover:border-red-300 hover:shadow-md">
                    <div class="flex items-center">
                        <div class="w-12 h-12 mr-4 bg-gray-200 rounded-lg"></div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Air Jordan 1 Retro</h3>
                            <p class="text-sm text-gray-600">Premium Sneakers</p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('product.detail', 15) }}" class="block p-4 transition-all duration-200 border border-gray-200 rounded-lg hover:border-red-300 hover:shadow-md">
                    <div class="flex items-center">
                        <div class="w-12 h-12 mr-4 bg-gray-200 rounded-lg"></div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Ulu Paung Mahogani</h3>
                            <p class="text-sm text-gray-600">Traditional Batak Style</p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('product.detail', 16) }}" class="block p-4 transition-all duration-200 border border-gray-200 rounded-lg hover:border-red-300 hover:shadow-md">
                    <div class="flex items-center">
                        <div class="w-12 h-12 mr-4 bg-gray-200 rounded-lg"></div>
                        <div>
                            <h3 class="font-semibold text-gray-900">VNY Collection</h3>
                            <p class="text-sm text-gray-600">Modern Design</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- SEO Information -->
        <div class="p-6 mt-8 bg-gray-50 rounded-lg">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">📊 Informasi SEO</h3>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <h4 class="font-medium text-gray-700">Terakhir Update:</h4>
                    <p class="text-sm text-gray-600">{{ now()->format('d M Y, H:i') }} WIB</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-700">Total Halaman:</h4>
                    <p class="text-sm text-gray-600">10+ halaman statis & dinamis</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection