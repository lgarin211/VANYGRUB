@extends('layouts.app')

@section('title', 'Keranjang - VNY Store')

@section('styles')
<style>
/* Cart specific styles */
.cart-item {
    transition: all 0.3s ease;
}

.cart-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.quantity-btn {
    transition: all 0.2s ease;
}

.quantity-btn:hover {
    background-color: #f87171;
    color: white;
}

.loading-spinner {
    border: 2px solid #f3f4f6;
    border-top: 2px solid #dc2626;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.empty-cart {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection

@section('content')
<!-- Navbar -->
@include('components.vny-navbar', ['currentPage' => 'cart', 'cartCount' => 0])

<!-- Cart Page -->
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-red-600 transition-colors">Home</a>
            <span class="text-gray-400">/</span>
            <span class="text-red-600 font-medium">Keranjang Belanja</span>
        </div>

        <!-- Page Title -->
        <h1 class="text-2xl font-bold text-gray-900 mb-8">Keranjang Belanja</h1>
        <p class="text-gray-600 mb-6">Kelola produk yang ingin Anda beli</p>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Section - Cart Items -->
            <div class="lg:w-2/3">
                <!-- Loading State -->
                <div id="loadingState" class="bg-white rounded-lg shadow-sm p-8 text-center">
                    <div class="loading-spinner mx-auto mb-4"></div>
                    <p class="text-gray-500">Memuat keranjang...</p>
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="bg-white rounded-lg shadow-sm p-8 text-center hidden">
                    <div class="text-6xl text-gray-300 mb-4">🛒</div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Keranjang Kosong</h3>
                    <p class="text-gray-500 mb-6">Belum ada item di keranjang Anda</p>
                    <a href="{{ route('vny.product') }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Mulai Belanja
                    </a>
                </div>

                <!-- Cart Items -->
                <div id="cartItemsSection" class="hidden">
                    <!-- Header -->
                    <div class="bg-white rounded-t-lg shadow-sm px-6 py-4 border-b">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">Item dalam Keranjang (<span id="itemCount">0</span>)</h2>
                            <button onclick="clearAllCart()" class="text-red-600 hover:text-red-700 font-medium text-sm">
                                Hapus Semua
                            </button>
                        </div>
                    </div>

                    <!-- Items List -->
                    <div class="bg-white rounded-b-lg shadow-sm">
                        <div id="cartItemsList" class="divide-y divide-gray-200">
                            <!-- Items will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- Continue Shopping -->
                    <div class="mt-6">
                        <button onclick="window.location.href='{{ route('vny.product') }}'"
                                class="inline-flex items-center text-red-600 hover:text-red-700 font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Lanjut Belanja
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Section - Order Summary -->
            <div class="lg:w-1/3">
                <div class="bg-white rounded-lg shadow-sm sticky top-6">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Ringkasan Pesanan</h2>

                        <!-- Promo Code Section -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kode Promo</label>
                            <div class="flex gap-2">
                                <input type="text"
                                       id="promoCode"
                                       placeholder="Masukkan kode promo"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm">
                                <button type="button"
                                        onclick="applyPromoCode()"
                                        class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                    Gunakan
                                </button>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div id="orderSummary">
                            <!-- Loading Summary -->
                            <div id="summaryLoading" class="space-y-3">
                                <div class="animate-pulse">
                                    <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                                    <div class="h-4 bg-gray-200 rounded w-1/2 mb-2"></div>
                                    <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                                </div>
                            </div>

                            <!-- Summary Content -->
                            <div id="summaryContent" class="hidden space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal (<span id="itemCountSummary">0</span> item)</span>
                                    <span id="subtotal" class="font-medium">Rp 0</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Pajak (PPN 10%)</span>
                                    <span id="tax" class="font-medium">Rp 0</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Ongkos kirim</span>
                                    <span id="shipping" class="font-medium">Rp 0</span>
                                </div>

                                <hr class="my-4">

                                <div class="flex justify-between text-lg font-semibold">
                                    <span class="text-gray-900">Total</span>
                                    <span id="total" class="text-red-600">Rp 0</span>
                                </div>

                                <button id="checkoutBtn"
                                        class="w-full mt-6 px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
                                        disabled>
                                    Checkout Sekarang
                                </button>

                                <p class="text-xs text-gray-500 text-center mt-3 flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Pembayaran aman dengan SSL
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Modal -->
<div id="checkoutModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b">
            <h2 class="text-xl font-semibold text-gray-900">Checkout - Data Pembeli</h2>
            <button onclick="closeCheckoutModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="flex flex-col lg:flex-row">
            <!-- Left side - Form -->
            <div class="lg:w-2/3 p-6">
                <form id="checkoutForm" class="space-y-6">
                    <!-- Informasi Otomatis -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-blue-900 mb-1">Akun Otomatis</h4>
                                <p class="text-sm text-blue-800">Data yang Anda isi akan digunakan untuk membuat akun pelanggan secara otomatis. Ini memudahkan Anda untuk melacak pesanan dan berbelanja di masa mendatang.</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-sm text-gray-700">Lengkapi data di bawah untuk mengirim pesanan via WhatsApp ke admin VNY Store</p>
                    </div>

                    <!-- Customer Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="customerName" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="customerName" name="customer_name" required
                                   placeholder="Masukkan nama lengkap"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        </div>

                        <div>
                            <label for="customerPhone" class="block text-sm font-medium text-gray-700 mb-1">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="customerPhone" name="customer_phone" required
                                   placeholder="08xxxxxxxxx"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        </div>
                    </div>

                    <div>
                        <label for="customerEmail" class="block text-sm font-medium text-gray-700 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="customerEmail" name="customer_email" required
                               placeholder="example@email.com"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    </div>

                    <div>
                        <label for="shippingAddress" class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea id="shippingAddress" name="shipping_address" required rows="3"
                                  placeholder="Jalan, Nomor Rumah, RT/RW, Kelurahan, Kecamatan"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="shippingCity" class="block text-sm font-medium text-gray-700 mb-1">
                                Kota <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="shippingCity" name="shipping_city" required
                                   placeholder="Jakarta"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        </div>

                        <div>
                            <label for="shippingPostalCode" class="block text-sm font-medium text-gray-700 mb-1">
                                Kode Pos <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="shippingPostalCode" name="shipping_postal_code" required
                                   placeholder="12345"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        </div>
                    </div>

                    <div>
                        <label for="orderNotes" class="block text-sm font-medium text-gray-700 mb-1">
                            Catatan Tambahan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="orderNotes" name="notes" required rows="3"
                                  placeholder="Masukkan catatan untuk pesanan (wajib diisi)"
                                  class="w-full px-3 py-2 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none border-red-200 bg-red-50"></textarea>
                    </div>
                </form>
            </div>

            <!-- Right side - Order Summary -->
            <div class="lg:w-1/3 bg-gray-50 p-6 border-l">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h3>

                <!-- Cart Items Summary -->
                <div id="checkoutItemsList" class="space-y-3 mb-4">
                    <!-- Items will be populated by JavaScript -->
                </div>

                <!-- Price Summary -->
                <div class="border-t border-gray-200 pt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span id="checkoutSubtotal" class="font-medium">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Pajak (PPN 10%)</span>
                        <span id="checkoutTax" class="font-medium">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Ongkir kirim</span>
                        <span id="checkoutShipping" class="font-medium">Rp 0</span>
                    </div>
                    <hr class="my-3">
                    <div class="flex justify-between text-lg font-semibold">
                        <span class="text-gray-900">Total</span>
                        <span id="checkoutTotal" class="text-red-600">Rp 0</span>
                    </div>
                </div>

                <!-- Checkout Actions -->
                <div class="mt-6 space-y-3">
                    <button id="checkoutSubmitBtn" onclick="processCheckout()" disabled
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-400 text-white font-medium rounded-lg transition-colors disabled:cursor-not-allowed">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.594z"/>
                        </svg>
                        <span id="checkoutBtnText">Lengkapi Data Dulu</span>
                    </button>

                    <p class="text-xs text-gray-500 text-center">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Pesanan akan dikirim ke: +62 821-1142-4592
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Cart functionality
let cartSessionId = localStorage.getItem('cart_session_id') || null;

document.addEventListener('DOMContentLoaded', function() {
    loadCart();
});

async function loadCart() {
    if (!cartSessionId) {
        showEmptyCart();
        return;
    }

    try {
        const response = await fetch(`/api/vny/cart?session_id=${cartSessionId}`);
        const data = await response.json();

        if (response.ok && data.success) {
            displayCart(data.data);
        } else {
            showEmptyCart();
        }
    } catch (error) {
        console.error('Error loading cart:', error);
        showEmptyCart();
    }
}

function displayCart(cartData) {
    const { items, summary } = cartData;

    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('summaryLoading').classList.add('hidden');

    if (items.length === 0) {
        showEmptyCart();
        return;
    }

    // Show cart items
    document.getElementById('cartItemsList').classList.remove('hidden');

    // Show cart items section
    document.getElementById('cartItemsSection').classList.remove('hidden');
    document.getElementById('itemCount').textContent = items.length;

    // Render items
    const itemsList = document.getElementById('cartItemsList');
    itemsList.innerHTML = items.map(item => {
        // Debug log for quantity
        console.log('Cart item:', item.name, 'Quantity:', item.quantity, 'ID:', item.id);

        // Prioritize selected image, then fall back to product image
        const imageUrl = item.image || item.selected_image || item.product?.main_image || item.product?.image || 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=80&h=80&fit=crop';

        // Format color and size display
        let variants = [];
        if (item.color) variants.push(`Warna: ${item.color}`);
        if (item.size) variants.push(`Ukuran: ${item.size}`);
        const variantText = variants.join(' • ');

        return `
        <div class="flex items-center justify-between p-6 hover:bg-gray-50 transition-colors">
            <div class="flex items-center gap-4 flex-1">
                <!-- Product Image -->
                <div class="flex-shrink-0">
                    <img src="${imageUrl}"
                         alt="${item.name || 'Product'}"
                         onerror="this.src='https://images.unsplash.com/photo-1549298916-b41d501d3772?w=80&h=80&fit=crop&crop=center'"
                         class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                </div>

                <!-- Product Info -->
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-900 text-base mb-1">${item.name || 'Product Name'}</h3>
                    ${variantText ? `<p class="text-sm text-gray-500 mb-2">${variantText}</p>` : ''}
                    <p class="text-lg font-bold text-red-600">${item.price || item.formatted_price || 'Rp 0'}</p>

                    <!-- Quantity Controls -->
                    <div class="flex items-center gap-2 mt-3">
                        <span class="text-sm text-gray-600">Jumlah:</span>
                        <div class="flex items-center gap-1 border rounded-lg">
                            <button onclick="updateQuantity(${item.id}, ${(item.quantity || 1) - 1})"
                                    class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-l-lg transition-colors ${(item.quantity || 1) <= 1 ? 'opacity-50 cursor-not-allowed' : ''}"
                                    ${(item.quantity || 1) <= 1 ? 'disabled' : ''}>
                                <span class="text-lg">−</span>
                            </button>
                            <span class="w-12 text-center font-medium text-gray-900">${item.quantity || 1}</span>
                            <button onclick="updateQuantity(${item.id}, ${(item.quantity || 1) + 1})"
                                    class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-r-lg transition-colors">
                                <span class="text-lg">+</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Remove Button -->
            <button onclick="removeItem(${item.id})"
                    class="ml-4 p-2 text-gray-400 hover:text-red-600 transition-colors"
                    title="Hapus item">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
        `;
    }).join('');

    // Show summary
    document.getElementById('summaryContent').classList.remove('hidden');

    // Format prices properly
    const formatPrice = (price) => {
        if (typeof price === 'string' && price.startsWith('Rp')) return price;
        const numPrice = parseFloat(price) || 0;
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(numPrice).replace('IDR', 'Rp');
    };

    document.getElementById('itemCountSummary').textContent = items.length;
    document.getElementById('subtotal').textContent = summary.subtotal_formatted || formatPrice(summary.subtotal);
    document.getElementById('tax').textContent = summary.tax_formatted || formatPrice(summary.tax);
    document.getElementById('shipping').textContent = summary.shipping_formatted || formatPrice(summary.shipping);
    document.getElementById('total').textContent = summary.total_formatted || formatPrice(summary.total);
    document.getElementById('checkoutBtn').disabled = false;

    // Update cart count in navbar if available
    updateNavbarCartCount(summary.itemCount || items.length);
}

function showEmptyCart() {
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('summaryLoading').classList.add('hidden');
    document.getElementById('emptyState').classList.remove('hidden');
}

async function updateQuantity(itemId, newQuantity) {
    // Validate inputs
    if (!itemId || isNaN(newQuantity) || newQuantity < 1) {
        if (newQuantity < 1) {
            removeItem(itemId);
        }
        return;
    }

    // Show loading state for the specific item
    const quantitySpan = document.querySelector(`[onclick*="updateQuantity(${itemId}"]`)?.parentElement?.querySelector('span');
    if (quantitySpan) {
        const originalText = quantitySpan.textContent;
        quantitySpan.textContent = '...';

        try {
            const response = await fetch(`/api/vny/cart/${itemId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ quantity: parseInt(newQuantity) })
            });

            if (response.ok) {
                loadCart(); // Reload cart
            } else {
                // Restore original quantity on error
                quantitySpan.textContent = originalText;
                console.error('Failed to update quantity');
            }
        } catch (error) {
            // Restore original quantity on error
            quantitySpan.textContent = originalText;
            console.error('Error updating quantity:', error);
        }
    }
}

async function removeItem(itemId) {
    try {
        const response = await fetch(`/api/vny/cart/${itemId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
            }
        });

        if (response.ok) {
            loadCart(); // Reload cart
        }
    } catch (error) {
        console.error('Error removing item:', error);
    }
}

function updateNavbarCartCount(count) {
    // This function can be used to update cart count in navbar
    // For now, we'll store it in localStorage for other pages to use
    localStorage.setItem('cart_item_count', count || 0);
}

async function clearAllCart() {
    if (!confirm('Apakah Anda yakin ingin menghapus semua item dari keranjang?')) {
        return;
    }

    try {
        const response = await fetch('/api/vny/cart/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ session_id: cartSessionId })
        });

        if (response.ok) {
            loadCart(); // Reload cart
        }
    } catch (error) {
        console.error('Error clearing cart:', error);
    }
}

function applyPromoCode() {
    const promoCode = document.getElementById('promoCode').value.trim();
    if (!promoCode) {
        alert('Masukkan kode promo terlebih dahulu');
        return;
    }

    // For now, just show a message
    alert('Fitur kode promo akan segera tersedia');
}

// Global variable to store cart data for checkout
let currentCartData = null;

// Update the checkout button to open modal instead of direct checkout
document.addEventListener('DOMContentLoaded', function() {
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (checkoutBtn) {
        checkoutBtn.onclick = function() {
            if (!checkoutBtn.disabled) {
                openCheckoutModal();
            }
        };
    }
});

function openCheckoutModal() {
    if (!currentCartData || !currentCartData.items || currentCartData.items.length === 0) {
        alert('Keranjang kosong. Tambahkan produk terlebih dahulu.');
        return;
    }

    // Populate checkout modal with cart data
    populateCheckoutModal(currentCartData);

    // Setup form validation
    setTimeout(() => {
        setupFormValidation();
    }, 100); // Small delay to ensure DOM is ready

    // Show modal
    document.getElementById('checkoutModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Prevent body scroll
}

function closeCheckoutModal() {
    document.getElementById('checkoutModal').classList.add('hidden');
    document.body.style.overflow = ''; // Restore body scroll
}

// Form validation function
function validateCheckoutForm() {
    const requiredFields = [
        'customerName',
        'customerPhone',
        'customerEmail',
        'shippingAddress',
        'shippingCity',
        'shippingPostalCode',
        'orderNotes'
    ];

    let isValid = true;

    for (const fieldId of requiredFields) {
        const field = document.getElementById(fieldId);
        if (field) {
            const value = field.value.trim();
            if (!value) {
                isValid = false;
                break;
            }
        }
    }

    // Update button state
    const submitBtn = document.getElementById('checkoutSubmitBtn');
    const btnText = document.getElementById('checkoutBtnText');

    if (isValid) {
        submitBtn.disabled = false;
        submitBtn.className = 'w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors';
        btnText.textContent = 'Kirim ke WhatsApp';
    } else {
        submitBtn.disabled = true;
        submitBtn.className = 'w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-400 text-white font-medium rounded-lg transition-colors disabled:cursor-not-allowed';
        btnText.textContent = 'Lengkapi Data Dulu';
    }
}

// Add event listeners for real-time validation when modal opens
function setupFormValidation() {
    const requiredFields = [
        'customerName',
        'customerPhone',
        'customerEmail',
        'shippingAddress',
        'shippingCity',
        'shippingPostalCode',
        'orderNotes'
    ];

    requiredFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', validateCheckoutForm);
            field.addEventListener('blur', validateCheckoutForm);
        }
    });

    // Initial validation
    validateCheckoutForm();
}

function populateCheckoutModal(cartData) {
    const { items, summary } = cartData;

    // Populate items list in checkout modal
    const checkoutItemsList = document.getElementById('checkoutItemsList');
    checkoutItemsList.innerHTML = items.map(item => {
        const imageUrl = item.image || item.selected_image || item.product?.main_image || item.product?.image || 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=60&h=60&fit=crop';

        let variants = [];
        if (item.color) variants.push(`Warna: ${item.color}`);
        if (item.size) variants.push(`Ukuran: ${item.size}`);
        const variantText = variants.join(' • ');

        return `
        <div class="flex items-center gap-3">
            <img src="${imageUrl}"
                 alt="${item.name}"
                 class="w-12 h-12 object-cover rounded-lg border">
            <div class="flex-1 min-w-0">
                <h4 class="font-medium text-gray-900 text-sm">${item.name}</h4>
                ${variantText ? `<p class="text-xs text-gray-500">${variantText}</p>` : ''}
                <p class="text-sm font-medium text-red-600">${item.price} × ${item.quantity}</p>
            </div>
        </div>
        `;
    }).join('');

    // Populate price summary
    const formatPrice = (price) => {
        if (typeof price === 'string' && price.startsWith('Rp')) return price;
        const numPrice = parseFloat(price) || 0;
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(numPrice).replace('IDR', 'Rp');
    };

    document.getElementById('checkoutSubtotal').textContent = summary.subtotal_formatted || formatPrice(summary.subtotal);
    document.getElementById('checkoutTax').textContent = summary.tax_formatted || formatPrice(summary.tax);
    document.getElementById('checkoutShipping').textContent = summary.shipping_formatted || formatPrice(summary.shipping);
    document.getElementById('checkoutTotal').textContent = summary.total_formatted || formatPrice(summary.total);
}

async function processCheckout() {
    const form = document.getElementById('checkoutForm');
    const formData = new FormData(form);

    // Validate required fields
    const requiredFields = ['customer_name', 'customer_phone', 'customer_email', 'shipping_address', 'shipping_city', 'shipping_postal_code', 'notes'];
    let isValid = true;

    const fieldIdMapping = {
        'customer_name': 'customerName',
        'customer_phone': 'customerPhone',
        'customer_email': 'customerEmail',
        'shipping_address': 'shippingAddress',
        'shipping_city': 'shippingCity',
        'shipping_postal_code': 'shippingPostalCode',
        'notes': 'orderNotes'
    };

    for (const field of requiredFields) {
        const value = formData.get(field)?.trim();
        if (!value) {
            isValid = false;
            const input = document.getElementById(fieldIdMapping[field]);
            if (input) {
                input.classList.add('border-red-500');
                input.focus();
            }
            break;
        }
    }

    if (!isValid) {
        alert('Harap lengkapi semua field yang wajib diisi');
        return;
    }

    try {
        // Prepare order data based on OrderController requirements
        const orderData = {
            customer_name: formData.get('customer_name'),
            customer_email: formData.get('customer_email'),
            customer_phone: formData.get('customer_phone'),
            shipping_address: formData.get('shipping_address'),
            shipping_city: formData.get('shipping_city'),
            shipping_postal_code: formData.get('shipping_postal_code'),
            session_id: cartSessionId,
            items: currentCartData.items.map(item => ({
                product_id: item.product_id,
                quantity: item.quantity,
                price: parseFloat(item.originalPrice || item.price?.replace(/[^\d]/g, '') || 0),
                size: item.size,
                color: item.color
            })),
            total_amount: parseFloat(currentCartData.summary.total?.toString().replace(/[^\d]/g, '') || 0),
            notes: formData.get('notes')
        };

        // Show loading state
        const submitBtn = document.getElementById('checkoutSubmitBtn');
        const btnText = document.getElementById('checkoutBtnText');
        const originalText = btnText.textContent;
        submitBtn.disabled = true;
        btnText.innerHTML = `
            <div class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memproses...</span>
            </div>
        `;

        console.log('Sending order data:', orderData);

        const response = await fetch('/api/vny/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(orderData)
        });

        const result = await response.json();

        if (response.ok && result.success) {
            // Success - get order code first
            const orderCode = result.data.order_code || result.data.id;

            // Redirect to WhatsApp immediately while user interaction is still active
            await redirectToWhatsApp(orderData, orderCode);

            // Then close modal and cleanup
            closeCheckoutModal();

            // Clear cart after successful order
            await clearAllCart();

            // Show success message
            alert(`Pesanan berhasil dibuat dengan kode: ${orderCode}\n\nPesan WhatsApp telah dibuka di tab baru untuk mengirim detail pesanan ke admin VNY Store.`);

            // Clear the form
            form.reset();

        } else {
            // Error handling
            console.error('Checkout error:', result);
            alert(result.message || 'Gagal memproses pesanan. Silakan coba lagi.');
        }

    } catch (error) {
        console.error('Error during checkout:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
    } finally {
        // Restore button state
        const submitBtn = document.getElementById('checkoutSubmitBtn');
        const btnText = document.getElementById('checkoutBtnText');
        btnText.textContent = originalText;
        // Re-validate form to set proper button state
        validateCheckoutForm();
    }
}

// Update displayCart function to store cart data globally
const originalDisplayCart = displayCart;
displayCart = function(cartData) {
    // Store cart data globally for checkout
    currentCartData = cartData;

    // Call original function
    originalDisplayCart(cartData);
};

async function redirectToWhatsApp(orderData, orderCode) {
    try {
        // Generate order details message
        const orderMessage = generateOrderMessage(orderData, orderCode);

        // Use the specified WhatsApp number
        const waPhone = '6281315871101';

        // Create WhatsApp URL
        const whatsappUrl = `https://wa.me/${waPhone}?text=${encodeURIComponent(orderMessage)}`;

        console.log('Opening WhatsApp URL:', whatsappUrl);

        // Open WhatsApp in new tab
        const newWindow = window.open(whatsappUrl, '_blank', 'noopener,noreferrer');

        // Check if popup was blocked
        if (!newWindow) {
            // Fallback: try to navigate in current window
            if (confirm('Popup terblokir! Klik OK untuk membuka WhatsApp di tab ini, atau Cancel untuk membatalkan.')) {
                window.open(whatsappUrl, '_self');
            } else {
                // Copy to clipboard as final fallback
                if (navigator.clipboard) {
                    await navigator.clipboard.writeText(orderMessage);
                    alert('Link WhatsApp tidak dapat dibuka. Detail pesanan telah disalin ke clipboard. Silakan buka WhatsApp manual dan paste pesannya.');
                } else {
                    alert(`Link WhatsApp tidak dapat dibuka. Silakan buka manual:\n${whatsappUrl}`);
                }
            }
        }

    } catch (error) {
        console.error('Error redirecting to WhatsApp:', error);
        // Final fallback
        const defaultMessage = generateOrderMessage(orderData, orderCode);
        const fallbackUrl = `https://wa.me/6281315871101?text=${encodeURIComponent(defaultMessage)}`;

        alert(`Terjadi kesalahan saat membuka WhatsApp. Silakan klik link berikut:\n${fallbackUrl}`);
    }
}

function generateOrderMessage(orderData, orderCode) {
    const formatPrice = (price) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(price).replace('IDR', 'Rp');
    };

    // Create message template following the provided format
    let message = `VNY - Premium Sneakers Collection\n`;
    message += `Discover premium sneakers and footwear collection\n\n`;
    message += `www.vanygroup.id\n\n`;

    message += `*PESANAN BARU VNY STORE*\n\n`;
    message += `Kode Pesanan: ${orderCode}\n\n`;

    // Check out link - using the order code as identifier
    message += `*LINK CEK PESANAN (KLIK DI SINI):*\n`;
    message += `https://www.vanygroup.id/checkout/${orderCode}\n\n`;

    // Customer information
    message += `*Data Pembeli:*\n`;
    message += `Nama: ${orderData.customer_name.toUpperCase()}\n`;
    message += `Phone: ${orderData.customer_phone}\n`;
    message += `Email: ${orderData.customer_email}\n`;
    message += `Alamat: ${orderData.shipping_address}, ${orderData.shipping_city}. ${orderData.shipping_postal_code}\n`;
    message += `Jawa Barat 16157\n`;
    message += `Kota: KOTA BOGOR\n`;
    message += `Kode Pos: 81851\n`;
    message += `Catatan: beli\n\n`;

    // Order items details
    message += `*Detail Pesanan:*\n`;
    let subtotal = 0;

    orderData.items.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;

        message += `${index + 1}. ${currentCartData.items[index]?.name || 'Produk'}\n`;
        if (item.color) message += `   Warna: ${item.color}\n`;
        if (item.size) message += `   Ukuran: ${item.size}\n`;
        message += `   Qty: ${item.quantity}x\n`;
        message += `   Harga: ${formatPrice(item.price)}\n\n`;
    });

    // Total calculation
    message += `*Total: ${formatPrice(orderData.total_amount)}*\n\n`;

    message += `Terima kasih telah berbelanja di VNY Store!`;

    return message;
}
</script>

@include('components.vny-footer')

@endsection
