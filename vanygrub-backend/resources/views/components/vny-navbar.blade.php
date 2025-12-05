{{-- VNY Navbar Component --}}
@props([
    'currentPage' => 'home',
    'showSearch' => true,
    'cartCount' => 0,
    'transparent' => false
])

<header class="shadow-lg {{ $transparent ? 'bg-transparent' : 'vny-header-gradient' }}">
  <div class="flex items-center justify-between px-5 py-3 mx-auto max-w-7xl">
    <!-- Search Section -->
    <div class="flex items-center gap-2 text-sm font-medium text-white {{ !$showSearch ? 'invisible' : '' }}">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
      </svg>
      <span class="cursor-pointer hover:text-gray-200 transition-colors duration-200">Search</span>
    </div>

    <!-- Logo Section -->
    <div class="text-2xl font-bold tracking-wide text-white">
      <a href="{{ route('home') }}" class="hover:text-gray-200 transition-colors duration-200">VNY</a>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center gap-6">
      <a href="{{ route('vny.cart') ?? '#' }}" class="relative text-sm font-medium text-white transition-colors duration-200 hover:text-gray-200">
        CART
        @if($cartCount > 0)
          <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
            {{ $cartCount > 9 ? '9+' : $cartCount }}
          </span>
        @endif
      </a>
      <a href="#" class="text-sm font-medium text-white transition-colors duration-200 hover:text-gray-200">TRANSACTION</a>
    </div>
  </div>

  <!-- Navigation Menu -->
  <nav class="border-t border-red-400/30">
    <div class="px-5 mx-auto max-w-7xl">
      <ul class="flex items-center justify-center">
        <li>
          <a href="{{ route('home') }}"
             class="block px-6 py-3 text-sm font-medium text-white transition-all duration-200 hover:bg-white/10 {{ $currentPage === 'home' ? 'border-b-2 border-white bg-white/20' : '' }}">
            HOME
          </a>
        </li>
        <li>
          <a href="{{ route('vny.product') }}"
             class="block px-6 py-3 text-sm font-medium text-white transition-all duration-200 hover:bg-white/10 {{ $currentPage === 'product' ? 'border-b-2 border-white bg-white/20' : '' }}">
            PRODUCT
          </a>
        </li>
        <li>
          <a href="{{ route('vny.about') }}"
             class="block px-6 py-3 text-sm font-medium text-white transition-all duration-200 hover:bg-white/10 {{ $currentPage === 'about' ? 'border-b-2 border-white bg-white/20' : '' }}">
            ABOUT VNY
          </a>
        </li>
        <li>
          <a href="{{ route('vny.store') }}"
             class="block px-6 py-3 text-sm font-medium text-white transition-all duration-200 hover:bg-white/10 {{ $currentPage === 'gallery' ? 'border-b-2 border-white bg-white/20' : '' }}">
            GALLERY
          </a>
        </li>
      </ul>
    </div>
  </nav>
</header>

{{-- Optional: Add search modal or functionality --}}
@if($showSearch)
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add search functionality here if needed
    const searchElement = document.querySelector('.cursor-pointer');
    if (searchElement) {
        searchElement.addEventListener('click', function() {
            // Handle search click - could open modal or redirect
            console.log('Search clicked');
        });
    }
});
</script>
@endif
