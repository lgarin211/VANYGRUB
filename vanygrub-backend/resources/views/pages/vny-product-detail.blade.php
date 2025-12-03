@extends('layouts.app')

@section('title', $product['name'] . ' - VNY Store')

@section('content')
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
                        <div class="space-y-3">
                            <h3 class="text-lg font-semibold text-gray-900">Pilih Ukuran</h3>
                            <div class="grid grid-cols-6 gap-2">
                                @foreach($product['sizes'] as $size)
                                    <button class="px-2 py-2 text-sm font-medium text-center text-gray-700 transition-all bg-white border border-gray-300 rounded-lg hover:border-red-300 hover:bg-red-50 focus:border-red-500 focus:bg-red-50 size-option"
                                            onclick="selectSize(this, '{{ $size }}')">
                                        {{ $size }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Quantity Section -->
                    <div class="space-y-3">
                        <h3 class="text-lg font-semibold text-gray-900">Jumlah</h3>
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-gray-600">Qty:</span>
                            <div class="flex items-center border border-gray-300 rounded-lg">
                                <button onclick="decreaseQuantity()" class="p-2 text-gray-600 transition-colors hover:bg-gray-100">
                                    −
                                </button>
                                <span class="px-4 py-2 font-medium min-w-[50px] text-center" id="quantity">1</span>
                                <button onclick="increaseQuantity()" class="p-2 text-gray-600 transition-colors hover:bg-gray-100">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-4">
                        <button id="actionBtn" onclick="handleAction()"
                                class="w-full px-6 py-3 font-semibold text-white transition-all bg-gray-400 rounded-lg cursor-not-allowed">
                            Pilih Ukuran Dulu
                        </button>
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
    // Update all size buttons
    document.querySelectorAll('.size-option').forEach(btn => {
        btn.className = 'py-2 px-3 border border-gray-300 rounded-lg text-sm font-medium bg-white text-gray-700 hover:border-red-300 hover:bg-red-50 transition-all size-option';
    });

    // Update selected button
    button.className = 'py-2 px-3 border border-red-500 rounded-lg text-sm font-medium bg-red-600 text-white transition-all size-option';

    selectedSize = size;
    updateActionButton();
}

function updateActionButton() {
    const actionBtn = document.getElementById('actionBtn');
    const inStock = @json($product['inStock'] ?? true);

    if (inStock && selectedSize) {
        actionBtn.className = 'w-full py-3 px-6 rounded-lg font-semibold text-white bg-red-600 hover:bg-red-700 cursor-pointer transition-all';
        actionBtn.textContent = 'Pilih Ukuran Dulu';
        actionBtn.disabled = false;
    } else if (!inStock) {
        actionBtn.className = 'w-full py-3 px-6 rounded-lg font-semibold text-white bg-gray-400 cursor-not-allowed transition-all';
        actionBtn.textContent = 'Out of Stock';
        actionBtn.disabled = true;
    } else {
        actionBtn.className = 'w-full py-3 px-6 rounded-lg font-semibold text-white bg-gray-400 cursor-not-allowed transition-all';
        actionBtn.textContent = 'Pilih Ukuran Dulu';
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

    // Here you would typically add to cart via API
    alert(`Menambahkan ke keranjang:\n- Produk: {{ $product['name'] }}\n- Warna: ${selectedColor}\n- Ukuran: ${selectedSize}\n- Jumlah: ${quantity}`);
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
});
</script>
@endsection
