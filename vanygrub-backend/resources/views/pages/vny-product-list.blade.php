@extends('layouts.app')

@section('title', 'Koleksi Produk - VNY Store')

@section('styles')
<style>
/* Custom styles for product list page */
.filter-btn.active {
    background-color: #8B0000;
    color: white;
}

.filter-btn {
    transition: all 0.3s ease;
}

.filter-btn:hover {
    background-color: #DC143C;
    color: white;
}

.product-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.product-image {
    aspect-ratio: 1;
    overflow: hidden;
    background-color: #f8f9fa;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.price-tag {
    background-color: #DC143C;
    color: white;
    font-weight: bold;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.875rem;
}

.hot-badge {
    background-color: #DC143C;
    color: white;
    font-size: 0.75rem;
    font-weight: bold;
    padding: 2px 6px;
    border-radius: 12px;
    position: absolute;
    top: 8px;
    right: 8px;
}

.category-filter {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    padding: 20px;
    border-radius: 8px;
}

.search-input {
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 10px 16px;
    width: 100%;
}

.search-input:focus {
    outline: none;
    border-color: #8B0000;
    box-shadow: 0 0 0 3px rgba(139, 0, 0, 0.1);
}
</style>
@endsection

@section('content')
<!-- Header -->
@include('components.vny-navbar', ['currentPage' => 'product'])

<!-- Page Header -->
<div class="py-16 text-white vny-header-gradient">
  <div class="px-5 mx-auto max-w-7xl">
    <div class="text-center">
      <h1 class="mb-4 text-4xl font-bold md:text-5xl">Koleksi Produk</h1>
      <p class="text-xl text-gray-200">Temukan sepatu dan fashion terbaik dari VNY Store</p>
    </div>
  </div>
</div>

<!-- Main Content -->
<div class="py-8 bg-gray-50">
  <div class="px-5 mx-auto max-w-7xl">
    <div class="flex flex-col gap-8 lg:flex-row">

      <!-- Sidebar Filters -->
      <div class="lg:w-1/4">
        <div class="space-y-6">

          <!-- Search -->
          <div class="p-6 bg-white rounded-lg shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Cari Produk</h3>
            <form method="GET" action="{{ route('vny.product') }}">
              <input type="hidden" name="category" value="{{ $selectedCategory }}">
              <input type="hidden" name="price_range" value="{{ $priceRange ?? 'all' }}">
              <input type="hidden" name="sort" value="{{ $sortBy ?? 'default' }}">
              <div class="relative">
                <input type="text" name="search" value="{{ $searchQuery }}"
                       placeholder="Find Your Shoes"
                       class="w-full p-3 pr-12 border border-gray-300 rounded-lg search-input">
                <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                  </svg>
                </button>
              </div>
            </form>
          </div>

          <!-- Categories Filter -->
          <div class="p-6 bg-white rounded-lg shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Jenis</h3>
            <div class="space-y-2">
              <a href="{{ route('vny.product', ['category' => 'all', 'search' => $searchQuery, 'price_range' => $priceRange ?? 'all', 'sort' => $sortBy ?? 'default']) }}"
                 class="block px-4 py-2 rounded-lg filter-btn {{ $selectedCategory === 'all' ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
                Semua Kategori
              </a>

              <!-- Dynamic Categories -->
              @foreach($categories as $category)
              <a href="{{ route('vny.product', ['category' => $category['name'], 'search' => $searchQuery, 'price_range' => $priceRange ?? 'all', 'sort' => $sortBy ?? 'default']) }}"
                 class="block px-4 py-2 rounded-lg filter-btn {{ $selectedCategory === $category['name'] ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
                {{ $category['name'] }}
              </a>
              @endforeach
            </div>
          </div>          <!-- Price Filter -->
          <div class="p-6 bg-white rounded-lg shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Serial</h3>
            <div class="space-y-2">
              <label class="flex items-center">
                <input type="checkbox" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                <span class="ml-2 text-sm text-gray-700">Semua</span>
              </label>
              <label class="flex items-center">
                <input type="checkbox" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                <span class="ml-2 text-sm text-gray-700">Limited Edition</span>
              </label>
            </div>
          </div>

          <!-- Price Range Filter -->
          <div class="p-6 bg-white rounded-lg shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Harga</h3>
            <div class="space-y-2">
              <a href="{{ route('vny.product', ['category' => $selectedCategory, 'search' => $searchQuery, 'price_range' => 'all', 'sort' => $sortBy ?? 'default']) }}"
                 class="flex items-center px-4 py-2 rounded-lg filter-btn {{ ($priceRange ?? 'all') === 'all' ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
                <span class="text-sm">Semua Harga</span>
              </a>
              <a href="{{ route('vny.product', ['category' => $selectedCategory, 'search' => $searchQuery, 'price_range' => 'under_1m', 'sort' => $sortBy ?? 'default']) }}"
                 class="flex items-center px-4 py-2 rounded-lg filter-btn {{ ($priceRange ?? 'all') === 'under_1m' ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
                <span class="text-sm">Di bawah 1 Juta</span>
              </a>
              <a href="{{ route('vny.product', ['category' => $selectedCategory, 'search' => $searchQuery, 'price_range' => '1m_3m', 'sort' => $sortBy ?? 'default']) }}"
                 class="flex items-center px-4 py-2 rounded-lg filter-btn {{ ($priceRange ?? 'all') === '1m_3m' ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
                <span class="text-sm">1-3 Juta</span>
              </a>
              <a href="{{ route('vny.product', ['category' => $selectedCategory, 'search' => $searchQuery, 'price_range' => '3m_5m', 'sort' => $sortBy ?? 'default']) }}"
                 class="flex items-center px-4 py-2 rounded-lg filter-btn {{ ($priceRange ?? 'all') === '3m_5m' ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
                <span class="text-sm">3-5 Juta</span>
              </a>
              <a href="{{ route('vny.product', ['category' => $selectedCategory, 'search' => $searchQuery, 'price_range' => 'over_5m', 'sort' => $sortBy ?? 'default']) }}"
                 class="flex items-center px-4 py-2 rounded-lg filter-btn {{ ($priceRange ?? 'all') === 'over_5m' ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
                <span class="text-sm">Di atas 5 Juta</span>
              </a>
            </div>
          </div>

        </div>
      </div>

      <!-- Products Grid -->
      <div class="lg:w-3/4">

        <!-- Results Header -->
        <div class="flex items-center justify-between mb-6">
          <div class="text-gray-600">
            <span>Menampilkan <strong>{{ $products->count() }}</strong> produk</span>
            @if($searchQuery || $selectedCategory !== 'all' || ($priceRange ?? 'all') !== 'all')
            <div class="mt-2">
              <span class="text-sm text-gray-500">Filter aktif:</span>
              @if($searchQuery)
                <span class="inline-flex items-center px-2 py-1 ml-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                  Search: "{{ $searchQuery }}"
                  <a href="{{ route('vny.product', ['category' => $selectedCategory, 'price_range' => $priceRange ?? 'all', 'sort' => $sortBy ?? 'default']) }}" class="ml-1 text-blue-600 hover:text-blue-800">×</a>
                </span>
              @endif
              @if($selectedCategory !== 'all')
                <span class="inline-flex items-center px-2 py-1 ml-1 text-xs bg-green-100 text-green-800 rounded-full">
                  Kategori: {{ $selectedCategory }}
                  <a href="{{ route('vny.product', ['search' => $searchQuery, 'price_range' => $priceRange ?? 'all', 'sort' => $sortBy ?? 'default']) }}" class="ml-1 text-green-600 hover:text-green-800">×</a>
                </span>
              @endif
              @if(($priceRange ?? 'all') !== 'all')
                <span class="inline-flex items-center px-2 py-1 ml-1 text-xs bg-purple-100 text-purple-800 rounded-full">
                  Harga:
                  @switch($priceRange)
                    @case('under_1m') < 1 Juta @break
                    @case('1m_3m') 1-3 Juta @break
                    @case('3m_5m') 3-5 Juta @break
                    @case('over_5m') > 5 Juta @break
                  @endswitch
                  <a href="{{ route('vny.product', ['category' => $selectedCategory, 'search' => $searchQuery, 'sort' => $sortBy ?? 'default']) }}" class="ml-1 text-purple-600 hover:text-purple-800">×</a>
                </span>
              @endif
              <button onclick="clearFilters()" class="ml-2 text-xs text-red-600 hover:text-red-800 underline">Clear All</button>
            </div>
            @endif
          </div>
          <div class="flex items-center gap-2">
            <span class="text-sm text-gray-600">Sort by:</span>
            <select id="sortSelect" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" onchange="applySorting()">
              <option value="default" {{ ($sortBy ?? 'default') === 'default' ? 'selected' : '' }}>Default</option>
              <option value="price_low_high" {{ ($sortBy ?? 'default') === 'price_low_high' ? 'selected' : '' }}>Price: Low to High</option>
              <option value="price_high_low" {{ ($sortBy ?? 'default') === 'price_high_low' ? 'selected' : '' }}>Price: High to Low</option>
              <option value="name_a_z" {{ ($sortBy ?? 'default') === 'name_a_z' ? 'selected' : '' }}>Name: A to Z</option>
              <option value="name_z_a" {{ ($sortBy ?? 'default') === 'name_z_a' ? 'selected' : '' }}>Name: Z to A</option>
            </select>
          </div>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
          @foreach($products as $product)
          <div class="overflow-hidden bg-white rounded-lg shadow-sm product-card" onclick="goToProductDetail({{ $product['id'] ?? 'null' }})" style="cursor: pointer;">
            <div class="relative product-image">
              <img src="{{ $product['image'] ?? 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=300&h=300&fit=crop&crop=center' }}"
                   alt="{{ $product['name'] ?? 'Product' }}"
                   onerror="this.src='https://images.unsplash.com/photo-1549298916-b41d501d3772?w=300&h=300&fit=crop&crop=center'">
              <span class="hot-badge">Hot</span>
            </div>
            <div class="p-4">
              <h3 class="mb-2 text-lg font-semibold text-gray-900 line-clamp-1">{{ $product['name'] ?? 'Product Name' }}</h3>
              <p class="mb-3 text-sm text-gray-600 line-clamp-2">{{ $product['description'] ?? 'Sepatu Dengan Desain Batak Motif...' }}</p>
              <div class="flex items-center justify-between">
                <span class="text-lg font-bold text-gray-900">
                  Rp {{ number_format($product['price'] ?? 400000, 0, ',', '.') }}
                </span>
                <span class="hot-badge">Hot</span>
              </div>
            </div>
          </div>
          @endforeach
        </div>
        @else
        <div class="py-12 text-center">
          <div class="mb-4 text-6xl text-gray-300">📦</div>
          <h3 class="mb-2 text-xl font-semibold text-gray-900">Produk tidak ditemukan</h3>
          <p class="text-gray-600">Coba ubah filter atau kata kunci pencarian</p>
        </div>
        @endif

        <!-- Pagination -->
        @if($products->count() > 12)
        <div class="flex justify-center mt-8">
          <nav class="flex space-x-2">
            <button class="px-3 py-2 text-gray-500 border border-gray-300 rounded-lg hover:bg-gray-50">Previous</button>
            <button class="px-3 py-2 text-white bg-red-600 border border-red-600 rounded-lg">1</button>
            <button class="px-3 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">2</button>
            <button class="px-3 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">3</button>
            <button class="px-3 py-2 text-gray-500 border border-gray-300 rounded-lg hover:bg-gray-50">Next</button>
          </nav>
        </div>
        @endif

      </div>
    </div>
  </div>
</div>

<!-- Product Detail Modal (reuse dari vny-store.blade.php) -->
<div id="productModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50 backdrop-blur-sm">
  <div class="flex items-center justify-center min-h-screen p-4">
    <div class="relative max-w-4xl mx-auto transition-all duration-300 transform scale-95 bg-white shadow-2xl opacity-0 rounded-2xl" id="modalContent">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-6 border-b border-gray-200">
        <h3 class="text-2xl font-bold text-gray-900" id="modalTitle">Detail Produk</h3>
        <button onclick="closeProductModal()" class="p-2 text-gray-400 transition-colors duration-200 rounded-full hover:text-gray-600 hover:bg-gray-100">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="p-8">
        <!-- Simple Layout: Image, Title, Description -->
        <div class="text-center">
          <!-- Product Image -->
          <div class="mb-6">
            <div class="w-full max-w-md mx-auto overflow-hidden bg-gray-100 aspect-square rounded-xl">
              <img id="modalImage" src="" alt="" class="object-cover w-full h-full">
            </div>
          </div>

          <!-- Product Info -->
          <div class="max-w-2xl mx-auto space-y-4">
            <h2 id="modalProductTitle" class="text-2xl font-bold text-gray-900 md:text-3xl">Product Name</h2>
            <p id="modalDescription" class="text-base leading-relaxed text-gray-600 md:text-lg">Product description will appear here...</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Function to redirect to product detail page
function goToProductDetail(productId) {
    if (productId && productId !== 'null') {
        window.location.href = `{{ url('/vny/product') }}/${productId}`;
    }
}

// Legacy function for modal (kept for compatibility)
function showProductDetail(productId) {
    console.log('Opening product detail for ID:', productId);

    // Fallback product data
    const productData = {
        id: productId || 1,
        title: "Sepatu Etnik Premium VNY",
        name: "Sepatu Etnik Premium VNY",
        description: "Sepatu dengan desain etnik modern yang memadukan tradisi dan gaya kontemporer. Diproduksi dengan bahan berkualitas tinggi dan craftsmanship terbaik.",
        price: "Rp. 450.000",
        image: "https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600&h=600&fit=crop&crop=center",
        tag: "Premium Collection",
        category: "Sepatu Etnik"
    };

    openProductModal(productData);
}

function openProductModal(product) {
    const modal = document.getElementById('productModal');
    const modalContent = document.getElementById('modalContent');

    // Populate modal with product data
    document.getElementById('modalTitle').textContent = product.title || product.name || 'Detail Produk';
    document.getElementById('modalProductTitle').textContent = product.title || product.name || 'Premium Product';
    document.getElementById('modalDescription').textContent = product.description || 'Produk berkualitas tinggi dengan desain motif etnik khas yang memadukan tradisi dengan gaya modern';

    // Set main image
    const mainImage = product.image || 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600&h=600&fit=crop&crop=center';
    document.getElementById('modalImage').src = mainImage;

    // Show modal
    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.style.transform = 'scale(1)';
        modalContent.style.opacity = '1';
    }, 10);

    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

function closeProductModal() {
    const modal = document.getElementById('productModal');
    const modalContent = document.getElementById('modalContent');

    // Hide modal with animation
    modalContent.style.transform = 'scale(0.95)';
    modalContent.style.opacity = '0';

    setTimeout(() => {
        modal.classList.add('hidden');
        // Restore body scroll
        document.body.style.overflow = 'auto';
    }, 300);
}

// Function to handle sorting
function applySorting() {
    const sortSelect = document.getElementById('sortSelect');
    const sortValue = sortSelect.value;

    // Get current URL parameters
    const urlParams = new URLSearchParams(window.location.search);

    // Update sort parameter
    if (sortValue === 'default') {
        urlParams.delete('sort');
    } else {
        urlParams.set('sort', sortValue);
    }

    // Redirect with new parameters
    const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
    window.location.href = newUrl;
}

// Function to clear all filters
function clearFilters() {
    window.location.href = '{{ route("vny.product") }}';
}

// Function to apply multiple filters at once
function applyFilters() {
    const category = document.querySelector('input[name="filter_category"]:checked')?.value || 'all';
    const priceRange = document.querySelector('input[name="filter_price"]:checked')?.value || 'all';
    const search = document.querySelector('input[name="search"]')?.value || '';

    const params = new URLSearchParams();
    if (category !== 'all') params.set('category', category);
    if (priceRange !== 'all') params.set('price_range', priceRange);
    if (search) params.set('search', search);

    const newUrl = `{{ route('vny.product') }}?${params.toString()}`;
    window.location.href = newUrl;
}
</script>
@endsection
