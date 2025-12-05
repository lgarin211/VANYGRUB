@extends('layouts.app')

@section('title', $product['name'] . ' - VNY Store')

@section('description', $product['shortDescription'] ?? $product['description'] ?? 'Premium quality footwear from VNY Store with authentic Batak ethnic design.')

@section('og_title', $product['name'] . ' - VNY Store')
@section('og_description', $product['shortDescription'] ?? $product['description'] ?? 'Premium quality footwear from VNY Store')
@section('og_image', $product['mainImage'] ?? $product['image'] ?? 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQdOGheV5b6MiiOF3tc7Sam_QMPFqPEwTEzZA&s')

@section('structured_data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "{{ $product['name'] }}",
    "description": "{{ $product['description'] ?? 'Premium quality footwear from VNY Store' }}",
    "image": "{{ $product['mainImage'] ?? $product['image'] }}",
    "sku": "{{ $product['sku'] ?? 'VNY-' . $product['id'] }}",
    "brand": {
        "@type": "Brand",
        "name": "VNY Store"
    },
    "offers": {
        "@type": "Offer",
        "price": "{{ $product['salePrice'] ?? $product['price'] }}",
        "priceCurrency": "IDR",
        "availability": "{{ ($product['inStock'] ?? true) ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
        "seller": {
            "@type": "Organization",
            "name": "VNY Store"
        }
    },
    @if(!empty($product['colors']))
    "color": [
        @foreach($product['colors'] as $color)
        "{{ $color }}"{{ !$loop->last ? ',' : '' }}
        @endforeach
    ],
    @endif
    @if(!empty($product['sizes']))
    "size": [
        @foreach($product['sizes'] as $size)
        "{{ $size }}"{{ !$loop->last ? ',' : '' }}
        @endforeach
    ],
    @endif
    "category": "{{ $product['category']['name'] ?? 'Footwear' }}",
    "manufacturer": {
        "@type": "Organization",
        "name": "VANY GROUP"
    }
}
</script>
@endsection

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="session-id" content="{{ session()->getId() }}">
@endsection

@section('styles')
<style>
/* Size Selection Enhancements */
.size-option {
    position: relative;
    overflow: hidden;
}

/* Hover effect dihandle langsung oleh Tailwind classes */

.size-option.selected {
    animation: pulseSize 0.3s ease-in-out;
}

@keyframes pulseSize {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1.05); }
}

/* Color Selection Enhancements */
.color-option {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.color-option:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Action Button Loading Animation */
.btn-loading {
    position: relative;
    overflow: hidden;
}

.btn-loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% { left: -100%; }
    100% { left: 100%; }
}

/* Responsive Grid Adjustments */
@media (max-width: 640px) {
    .size-option {
        font-size: 0.875rem;
        padding: 0.625rem 0.5rem;
    }
}

/* Success feedback animation */
@keyframes successPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(34, 197, 94, 0.3); }
    100% { transform: scale(1); }
}

.success-animation {
    animation: successPulse 0.6s ease-in-out;
}
</style>
@endsection

@section('content')
<!-- Navbar -->
@include('components.vny-navbar', ['currentPage' => 'product'])

<div class="min-h-screen bg-gray-100">
    <!-- Breadcrumb -->
    <div class="py-4 bg-gray-50">
        <div class="container px-4 mx-auto">
            <div class="flex items-center space-x-2 text-sm">
                <a href="/" class="text-red-600 hover:text-red-700">Vany Group</a>
                <span class="text-gray-400">/</span>
                <a href="/vny" class="text-red-600 hover:text-red-700">VNY Store</a>
                <span class="text-gray-400">/</span>
                <a href="{{ route('vny.product') }}" class="text-red-600 hover:text-red-700">Product</a>
                <span class="text-gray-400">/</span>
                <span class="font-medium text-gray-900">{{ $product['name'] ?? 'Product Detail' }}</span>
            </div>
        </div>
    </div>

    <div class="container px-4 py-8 mx-auto">
        <!-- Back to Product Link -->
        <div class="mb-6">
            <a href="{{ route('vny.product') }}" class="flex items-center text-red-600 hover:text-red-800">
                ← Kembali ke Produk
            </a>
        </div>

        <div class="bg-white">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <!-- Product Images Section -->
                <div class="space-y-4">
                    <!-- Main Image with Navigation -->
                    <div class="relative overflow-hidden bg-gray-100 rounded-lg" style="aspect-ratio: 4/3;">
                        <img id="mainProductImage"
                             src="{{ $product['mainImage'] ?? $product['image'] }}"
                             alt="{{ $product['name'] }}"
                             class="object-cover w-full h-full" style="object-position: bottom;">

                        <!-- Navigation Arrows -->
                        @if(count($product['gallery'] ?? []) > 1)
                            <button onclick="previousImage()" class="absolute p-2 text-white transition-all transform -translate-y-1/2 rounded-full left-4 top-1/2 bg-black/50 hover:bg-black/70">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button onclick="nextImage()" class="absolute p-2 text-white transition-all transform -translate-y-1/2 rounded-full right-4 top-1/2 bg-black/50 hover:bg-black/70">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        @endif

                        <!-- Color Label (bottom left) -->
                        <div class="absolute px-2 py-1 text-sm text-white rounded bottom-3 left-3 bg-black/70">
                            <span id="selectedColorDisplay">{{ $product['colors'][0] ?? 'Mahogani' }}</span>
                        </div>

                        <!-- Image Counter (bottom right) -->
                        <div class="absolute px-2 py-1 text-sm text-white rounded bottom-3 right-3 bg-black/70">
                            <span id="imageCounter">1/{{ count($product['gallery'] ?? []) + 1 }}</span>
                        </div>
                    </div>

                    <!-- Thumbnail Images Grid -->
                    <div class="flex flex-wrap justify-start gap-3">
                        <!-- Main Image Thumbnail -->
                        <div class="w-24 h-24 overflow-hidden transition-all bg-gray-100 border-2 border-red-500 rounded-lg cursor-pointer thumbnail-item active hover:opacity-80"
                             onclick="changeMainImage('{{ $product['mainImage'] ?? $product['image'] }}', 0)">
                            <img src="{{ $product['mainImage'] ?? $product['image'] }}"
                                 alt="Main"
                                 class="object-cover w-full h-full">
                        </div>

                        @foreach($product['gallery'] as $index => $image)
                            <div class="w-24 h-24 overflow-hidden transition-all bg-gray-100 border-2 border-transparent rounded-lg cursor-pointer hover:border-gray-400 hover:opacity-80 thumbnail-item"
                                 onclick="changeMainImage('{{ $image }}', {{ $index + 1 }})">
                                <img src="{{ $image }}"
                                     alt="Gallery {{ $index + 1 }}"
                                     class="object-cover w-full h-full">
                            </div>
                        @endforeach

                        <!-- 3D View Button -->
                        <div class="flex items-center justify-center w-24 h-24 text-white transition-all bg-blue-600 rounded-lg cursor-pointer hover:bg-blue-700">
                            <div class="text-center">
                                <div class="mb-1 text-lg">🌐</div>
                                <div class="text-xs font-semibold">3D</div>
                            </div>
                        </div>
                    </div>

                    <!-- View Toggle Buttons -->
                    <div class="flex space-x-2">
                        <button class="flex items-center justify-center flex-1 px-4 py-2 space-x-2 font-medium text-white bg-red-600 rounded-lg">
                            <span>📷</span>
                            <span>Images ({{ count($product['gallery'] ?? []) + 1 }})</span>
                        </button>
                        <button class="flex items-center justify-center flex-1 px-4 py-2 space-x-2 font-medium text-white bg-blue-600 rounded-lg">
                            <span>🔄</span>
                            <span>3D View</span>
                        </button>
                    </div>

                    <!-- Warning Note -->
                    <div class="p-3 border border-yellow-200 rounded-lg bg-yellow-50">
                        <p class="text-sm text-yellow-800">
                            <strong>Catatan:</strong> Warna produk aktual mungkin sedikit berbeda dari gambar yang ditampilkan. Pilih warna di bawah untuk melihat varian yang tersedia.
                        </p>
                    </div>
                </div>

                <!-- Product Info Section -->
                <div class="p-6 space-y-6">
                    <!-- Product Header -->
                    <div>
                        <div class="flex items-center mb-2 space-x-2">
                            <span class="px-2 py-1 text-xs font-bold text-white bg-red-600 rounded">
                                LIMITED EDITION
                            </span>
                            <span class="px-2 py-1 text-xs font-bold text-white bg-green-600 rounded">
                                IN STOCK
                            </span>
                        </div>
                        <h1 class="mb-2 text-2xl font-bold text-gray-900">{{ $product['name'] }}</h1>
                        <p class="mb-4 text-gray-600">{{ $product['shortDescription'] ?? $product['description'] }}</p>

                        <!-- Price -->
                        <div class="mb-6">
                            <span class="text-3xl font-bold text-red-600">Rp {{ number_format($product['price'], 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Product Specs Quick Info -->
                    <div class="grid grid-cols-2 text-sm gap-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Berat</span>
                            <span class="font-medium">{{ $product['weight'] ?? '1000,00' }}g</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Asal</span>
                            <span class="font-medium">{{ $product['countryOrigin'] ?? 'Batak' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Garansi</span>
                            <span class="font-medium">{{ $product['warranty'] ?? '6 Bulan' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Riils</span>
                            <span class="font-medium">Real Pict</span>
                        </div>
                    </div>

                    <!-- Color Selection -->
                    @if(!empty($product['colors']))
                        <div class="space-y-3">
                            <h3 class="text-lg font-semibold text-gray-900">Pilih Warna</h3>
                            <div class="flex items-center gap-2 text-sm">
                                <span class="text-gray-600">Warna saat ini:</span>
                                <span class="font-medium text-red-600" id="selectedColor">{{ $product['colors'][0] }}</span>
                            </div>
                            <div class="flex gap-2">
                                @foreach($product['colors'] as $index => $color)
                                    <button class="px-4 py-2 border rounded-lg text-sm font-medium transition-all {{ $index === 0 ? 'border-red-500 bg-red-600 text-white' : 'border-gray-300 bg-white text-gray-700 hover:border-red-300' }} color-option"
                                            onclick="selectColor(this, '{{ $color }}')">
                                        {{ $color }}
                                        @if($index === 0)
                                            <span class="ml-2">✓</span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Size Selection -->
                    @if(!empty($product['sizes']))
                        <div class="space-y-4">
                            <div class="flex items-center space-x-2">
                                <h3 class="text-lg font-semibold text-gray-900">Pilih Ukuran</h3>
                                <span class="px-2 py-1 text-xs text-gray-600 bg-gray-100 rounded-full">
                                    Warna saat ini: <span id="selectedColorDisplay">{{ $product['colors'][0] ?? 'Mahogani' }}</span>
                                </span>
                            </div>

                            <!-- Size Grid - Responsive and Better Spacing -->
                            <div class="grid grid-cols-4 gap-3 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-8">
                                @foreach($product['sizes'] as $size)
                                    <button class="px-3 py-3 text-sm font-semibold text-center text-gray-700 transition-all duration-200 bg-white border-2 border-gray-300 rounded-lg size-option hover:border-red-500 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1"
                                            onclick="selectSize(this, '{{ $size }}')">
                                        <span class="block">{{ $size }}</span>


                                    </button>
                                @endforeach
                            </div>

                            <!-- Size Guide Link -->
                            <div class="flex items-center justify-between pt-2 text-sm">
                                <span class="text-gray-600">
                                    <span id="selectedSizeText" class="font-medium text-red-600">Belum dipilih</span>
                                </span>
                                <button class="flex items-center space-x-1 text-red-600 hover:text-red-700 hover:underline">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span>Panduan Ukuran</span>
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Quantity Section -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900">Jumlah</h3>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">Quantity:</span>
                            <div class="flex items-center bg-white border-2 border-gray-200 shadow-sm rounded-xl">
                                <button onclick="decreaseQuantity()"
                                        class="p-3 text-gray-600 transition-all duration-200 hover:bg-red-50 hover:text-red-600 rounded-l-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <div class="flex items-center justify-center min-w-[60px] px-4 py-3 bg-gray-50 border-x-2 border-gray-200">
                                    <span class="text-lg font-bold text-gray-800" id="quantity">1</span>
                                </div>
                                <button onclick="increaseQuantity()"
                                        class="p-3 text-gray-600 transition-all duration-200 hover:bg-red-50 hover:text-red-600 rounded-r-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Stock indicator -->
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Stok:</span>
                            <span class="flex items-center space-x-1">
                                @if($product['inStock'] ?? true)
                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                    <span class="font-medium text-green-600">Tersedia</span>
                                @else
                                    <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                    <span class="font-medium text-red-600">Habis</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-6 space-y-4">
                        <!-- Main Action Button -->
                        <button id="actionBtn" onclick="handleAction()"
                                class="w-full px-6 py-4 font-bold text-gray-600 transition-all bg-gray-200 border-2 border-gray-300 border-dashed cursor-not-allowed rounded-xl">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Pilih Ukuran Terlebih Dahulu</span>
                            </div>
                        </button>

                        <!-- Secondary Actions -->
                        <div class="grid grid-cols-2 gap-3">
                            <button onclick="toggleWishlist()" class="flex items-center justify-center px-4 py-3 space-x-2 text-gray-600 transition-all duration-200 bg-white border-2 border-gray-200 rounded-xl hover:border-red-300 hover:bg-red-50 hover:text-red-600" id="wishlistBtn">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="wishlistIcon">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span class="font-medium" id="wishlistText">Wishlist</span>
                            </button>
                            <button class="flex items-center justify-center px-4 py-3 space-x-2 text-gray-600 transition-all duration-200 bg-white border-2 border-gray-200 rounded-xl hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                </svg>
                                <span class="font-medium">Share</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Detail Description -->
            @if(!empty($product['detailedDescription']))
            <div class="pt-8 mt-12 border-t">
                <h2 class="mb-6 text-2xl font-bold">Detail Produk</h2>
                <div class="prose prose-lg max-w-none">
                    {!! $product['detailedDescription'] !!}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let selectedSize = null;
let selectedColor = @json($product['colors'][0] ?? null);
let quantity = 1;
let currentImageIndex = 0;
let totalImages = {{ count($product['gallery'] ?? []) + 1 }};
let allImages = [
    '{{ $product['mainImage'] ?? $product['image'] }}',
    @foreach($product['gallery'] as $image)
    '{{ $image }}',
    @endforeach
];

function changeMainImage(src, index) {
    currentImageIndex = index;
    document.getElementById('mainProductImage').src = src;
    document.getElementById('imageCounter').textContent = (index + 1) + '/' + totalImages;

    // Update thumbnail active state
    document.querySelectorAll('.thumbnail-item').forEach((thumb, i) => {
        if (i === index) {
            thumb.classList.remove('border-transparent');
            thumb.classList.add('border-red-500');
            thumb.classList.add('active');
        } else {
            thumb.classList.remove('border-red-500', 'active');
            thumb.classList.add('border-transparent');
        }
    });
}

function previousImage() {
    if (currentImageIndex > 0) {
        currentImageIndex--;
    } else {
        currentImageIndex = totalImages - 1;
    }
    changeMainImage(allImages[currentImageIndex], currentImageIndex);
}

function nextImage() {
    if (currentImageIndex < totalImages - 1) {
        currentImageIndex++;
    } else {
        currentImageIndex = 0;
    }
    changeMainImage(allImages[currentImageIndex], currentImageIndex);
}

function selectColor(button, color) {
    // Update all color buttons
    document.querySelectorAll('.color-option').forEach(btn => {
        btn.className = 'px-4 py-2 border rounded-lg text-sm font-medium transition-all border-gray-300 bg-white text-gray-700 hover:border-red-300 color-option';
        // Remove checkmark
        const checkmark = btn.querySelector('span:last-child');
        if (checkmark && checkmark.textContent === '✓') {
            checkmark.remove();
        }
    });

    // Update selected button
    button.className = 'px-4 py-2 border rounded-lg text-sm font-medium transition-all border-red-500 bg-red-600 text-white color-option';

    // Add checkmark if not exists
    if (!button.querySelector('span:last-child') || button.querySelector('span:last-child').textContent !== '✓') {
        const checkmark = document.createElement('span');
        checkmark.className = 'ml-2';
        checkmark.textContent = '✓';
        button.appendChild(checkmark);
    }

    // Update displays
    selectedColor = color;
    document.getElementById('selectedColor').textContent = color;
    document.getElementById('selectedColorDisplay').textContent = color;
}

function selectSize(button, size) {
    // Update all size buttons to default state
    document.querySelectorAll('.size-option').forEach(btn => {
        btn.className = 'size-option px-3 py-3 text-sm font-semibold text-center text-gray-700 transition-all duration-200 bg-white border-2 border-gray-300 rounded-lg hover:border-red-500 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1';
    });

    // Update selected button to active state
    button.className = 'size-option px-3 py-3 text-sm font-semibold text-center text-white transition-all duration-200 bg-red-600 border-2 border-red-600 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1';

    // Update selected size text and variables
    selectedSize = size;
    const selectedSizeText = document.getElementById('selectedSizeText');
    if (selectedSizeText) {
        selectedSizeText.textContent = `Ukuran ${size} dipilih`;
        selectedSizeText.classList.remove('text-gray-600');
        selectedSizeText.classList.add('text-red-600', 'font-semibold');
    }

    updateActionButton();
}

function updateActionButton() {
    const actionBtn = document.getElementById('actionBtn');
    const inStock = @json($product['inStock'] ?? true);

    if (inStock && selectedSize && selectedColor) {
        actionBtn.className = 'w-full py-4 px-6 rounded-xl font-bold text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 cursor-pointer transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-[1.02]';
        actionBtn.innerHTML = `
            <div class="flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 8M7 13l2.5 8M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"></path>
                </svg>
                <span>Tambah ke Keranjang</span>
            </div>
        `;
        actionBtn.disabled = false;
    } else if (!inStock) {
        actionBtn.className = 'w-full py-4 px-6 rounded-xl font-bold text-white bg-gray-500 cursor-not-allowed transition-all';
        actionBtn.innerHTML = `
            <div class="flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                </svg>
                <span>Stok Habis</span>
            </div>
        `;
        actionBtn.disabled = true;
    } else if (!selectedSize) {
        actionBtn.className = 'w-full py-4 px-6 rounded-xl font-bold text-gray-600 bg-gray-200 cursor-not-allowed transition-all border-2 border-dashed border-gray-300';
        actionBtn.innerHTML = `
            <div class="flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Pilih Ukuran Terlebih Dahulu</span>
            </div>
        `;
        actionBtn.disabled = true;
    } else {
        actionBtn.className = 'w-full py-4 px-6 rounded-xl font-bold text-gray-600 bg-gray-200 cursor-not-allowed transition-all border-2 border-dashed border-gray-300';
        actionBtn.innerHTML = `
            <div class="flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Pilih Warna & Ukuran</span>
            </div>
        `;
        actionBtn.disabled = true;
    }
}

function handleAction() {
    const inStock = @json($product['inStock'] ?? true);

    if (!inStock) {
        alert('Produk sedang tidak tersedia');
        return;
    }

    if (!selectedSize) {
        alert('Silakan pilih ukuran terlebih dahulu');
        return;
    }

    // Add to cart via API
    addToCart();
}

async function addToCart() {
    const actionBtn = document.getElementById('actionBtn');
    const originalContent = actionBtn.innerHTML;

    // Show loading state
    actionBtn.disabled = true;
    actionBtn.className = 'w-full py-4 px-6 rounded-xl font-bold text-white bg-gray-400 cursor-not-allowed transition-all';
    actionBtn.innerHTML = `
        <div class="flex items-center justify-center space-x-2">
            <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Menambahkan...</span>
        </div>
    `;

    try {
        // Check if user is authenticated
        const currentUser = window.Firebase ? window.Firebase.auth.getCurrentUser() : null;
        if (!currentUser) {
            // Show auth modal if user is not logged in
            actionBtn.disabled = false;
            actionBtn.innerHTML = originalContent;
            actionBtn.className = 'w-full py-4 px-6 rounded-xl font-bold text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 cursor-pointer transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-[1.02]';

            if (window.VNYAuth) {
                window.VNYAuth.showModal();
            } else {
                showError('Silakan login terlebih dahulu untuk menambah ke keranjang');
            }
            return;
        }

        // Get CSRF token and create a simple session identifier
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // Use Firebase user ID as session identifier
        let sessionId = currentUser.uid || localStorage.getItem('cart_session_id');
        if (!sessionId) {
            sessionId = 'cart_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            localStorage.setItem('cart_session_id', sessionId);
        }

        // Get the currently displayed image
        const currentImage = document.getElementById('mainProductImage').src;
        // Extract the storage path from the full URL (everything after /storage/)
        const storageIndex = currentImage.indexOf('/storage/');
        const currentImagePath = storageIndex !== -1 ? currentImage.substring(storageIndex + 9) : currentImage.split('/').pop();

        const response = await fetch('/api/vny/cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
            },
            body: JSON.stringify({
                product_id: {{ $product['id'] }},
                quantity: quantity,
                color: selectedColor,
                size: selectedSize,
                session_id: sessionId,
                selected_image: currentImagePath
            })
        });

        const data = await response.json();

        if (response.ok && data.success) {
            // Show success state
            actionBtn.className = 'w-full py-4 px-6 rounded-xl font-bold text-white bg-green-600 cursor-pointer transition-all';
            actionBtn.innerHTML = `
                <div class="flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Berhasil ditambahkan!</span>
                </div>
            `;

            // Show success message
            const successMsg = document.createElement('div');
            successMsg.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300';
            successMsg.innerHTML = `
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Produk berhasil ditambahkan ke keranjang</span>
                </div>
            `;
            document.body.appendChild(successMsg);

            // Remove success message after 3 seconds
            setTimeout(() => {
                successMsg.remove();
            }, 3000);

            // Reset button after 2 seconds
            setTimeout(() => {
                actionBtn.disabled = false;
                updateActionButton();
            }, 2000);

        } else {
            throw new Error(data.message || 'Gagal menambahkan produk');
        }

    } catch (error) {
        console.error('Error adding to cart:', error);

        // Show error state
        actionBtn.className = 'w-full py-4 px-6 rounded-xl font-bold text-white bg-red-600 cursor-pointer transition-all';
        actionBtn.innerHTML = `
            <div class="flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span>Gagal ditambahkan</span>
            </div>
        `;

        // Show error message
        const errorMsg = document.createElement('div');
        errorMsg.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300';
        errorMsg.innerHTML = `
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span>Gagal menambahkan produk: ${error.message}</span>
            </div>
        `;
        document.body.appendChild(errorMsg);

        // Remove error message after 4 seconds
        setTimeout(() => {
            errorMsg.remove();
        }, 4000);

        // Reset button after 3 seconds
        setTimeout(() => {
            actionBtn.disabled = false;
            updateActionButton();
        }, 3000);
    }
}

// Wishlist functions
async function toggleWishlist() {
    const currentUser = window.Firebase ? window.Firebase.auth.getCurrentUser() : null;
    if (!currentUser) {
        if (window.VNYAuth) {
            window.VNYAuth.showModal();
        } else {
            showError('Silakan login terlebih dahulu untuk menambah ke wishlist');
        }
        return;
    }

    const wishlistBtn = document.getElementById('wishlistBtn');
    const wishlistIcon = document.getElementById('wishlistIcon');
    const wishlistText = document.getElementById('wishlistText');

    // Check if already in wishlist (simple check with localStorage for now)
    const productId = @json($product['id']);
    const wishlistKey = `wishlist_${currentUser.uid}_${productId}`;
    const isInWishlist = localStorage.getItem(wishlistKey);

    try {
        if (isInWishlist) {
            // Remove from wishlist
            localStorage.removeItem(wishlistKey);

            // Update UI to unfilled heart
            wishlistIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>';
            wishlistBtn.className = 'flex items-center justify-center px-4 py-3 space-x-2 text-gray-600 transition-all duration-200 bg-white border-2 border-gray-200 rounded-xl hover:border-red-300 hover:bg-red-50 hover:text-red-600';
            wishlistText.textContent = 'Wishlist';

            showMessage('Dihapus dari wishlist', 'success');
        } else {
            // Add to wishlist
            localStorage.setItem(wishlistKey, 'true');

            // Update UI to filled heart
            wishlistIcon.innerHTML = '<path fill="currentColor" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>';
            wishlistBtn.className = 'flex items-center justify-center px-4 py-3 space-x-2 text-red-600 transition-all duration-200 bg-red-50 border-2 border-red-300 rounded-xl hover:border-red-400 hover:bg-red-100';
            wishlistText.textContent = 'In Wishlist';

            showMessage('Ditambahkan ke wishlist', 'success');

            // Save to Firebase Firestore
            if (window.Firebase && window.Firebase.firestore) {
                try {
                    await window.Firebase.firestore.addDocument('wishlists', {
                        userId: currentUser.uid,
                        productId: productId,
                        productName: @json($product['name']),
                        productImage: @json($product['image'] ?? $product['mainImage']),
                        productPrice: @json($product['price']),
                    });
                } catch (error) {
                    console.warn('Could not sync to Firebase:', error);
                }
            }
        }
    } catch (error) {
        console.error('Error toggling wishlist:', error);
        showError('Terjadi kesalahan. Silakan coba lagi.');
    }
}

// Check wishlist status on page load
function checkWishlistStatus() {
    const currentUser = window.Firebase ? window.Firebase.auth.getCurrentUser() : null;
    if (!currentUser) return;

    const productId = @json($product['id']);
    const wishlistKey = `wishlist_${currentUser.uid}_${productId}`;
    const isInWishlist = localStorage.getItem(wishlistKey);

    if (isInWishlist) {
        const wishlistBtn = document.getElementById('wishlistBtn');
        const wishlistIcon = document.getElementById('wishlistIcon');
        const wishlistText = document.getElementById('wishlistText');

        wishlistIcon.innerHTML = '<path fill="currentColor" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>';
        wishlistBtn.className = 'flex items-center justify-center px-4 py-3 space-x-2 text-red-600 transition-all duration-200 bg-red-50 border-2 border-red-300 rounded-xl hover:border-red-400 hover:bg-red-100';
        wishlistText.textContent = 'In Wishlist';
    }
}

// Helper function for showing messages
function showMessage(message, type = 'info') {
    const messageDiv = document.createElement('div');
    messageDiv.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    messageDiv.textContent = message;

    document.body.appendChild(messageDiv);

    setTimeout(() => {
        messageDiv.remove();
    }, 3000);
}

function decreaseQuantity() {
    if (quantity > 1) {
        quantity--;
        document.getElementById('quantity').textContent = quantity;
    }
}

function increaseQuantity() {
    const maxQuantity = @json($product['stockQuantity'] ?? 10);
    if (quantity < maxQuantity) {
        quantity++;
        document.getElementById('quantity').textContent = quantity;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateActionButton();

    // Set initial color display
    if (selectedColor) {
        document.getElementById('selectedColorDisplay').textContent = selectedColor;
    }

    // Check wishlist status when page loads
    setTimeout(() => {
        checkWishlistStatus();
    }, 1000); // Wait for Firebase to initialize
});
</script>
@endsection
