<!-- Header Component -->
<header class="bg-white shadow-md">
    <div class="container mx-auto px-4">
        <!-- Navigation Bar -->
        <nav class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="VANY GROUB" class="h-10 w-auto">
                    <span class="ml-3 text-xl font-bold text-gray-900">VANY GROUB</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-red-600 transition-colors duration-200 {{ request()->routeIs('home') ? 'text-red-600 font-semibold' : '' }}">
                    Home
                </a>
                <a href="{{ route('vny.store') }}" class="text-gray-700 hover:text-red-600 transition-colors duration-200 {{ request()->routeIs('vny.store') || request()->routeIs('vny.products.*') ? 'text-red-600 font-semibold' : '' }}">
                    VNY Store
                </a>
                <a href="{{ route('vny.about') }}" class="text-gray-700 hover:text-red-600 transition-colors duration-200 {{ request()->routeIs('vny.about') ? 'text-red-600 font-semibold' : '' }}">
                    VNY About
                </a>
                <a href="{{ route('gallery') }}" class="text-gray-700 hover:text-red-600 transition-colors duration-200 {{ request()->routeIs('gallery') ? 'text-red-600 font-semibold' : '' }}">
                    Gallery
                </a>
                <a href="{{ route('about') }}" class="text-gray-700 hover:text-red-600 transition-colors duration-200 {{ request()->routeIs('about') ? 'text-red-600 font-semibold' : '' }}">
                    About
                </a>
                <a href="{{ route('transactions') }}" class="text-gray-700 hover:text-red-600 transition-colors duration-200 {{ request()->routeIs('transactions') ? 'text-red-600 font-semibold' : '' }}">
                    Transactions
                </a>
            </div>

            <!-- Cart and User Actions -->
            <div class="flex items-center space-x-4">
                <!-- Cart Icon -->
                <button class="relative p-2 text-gray-700 hover:text-red-600 transition-colors duration-200">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" id="cart-count">0</span>
                </button>

                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2 text-gray-700" id="mobile-menu-btn">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </nav>

        <!-- Mobile Navigation -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-700 hover:text-red-600 {{ request()->routeIs('home') ? 'text-red-600 font-semibold' : '' }}">Home</a>
                <a href="{{ route('vny.store') }}" class="block px-3 py-2 text-gray-700 hover:text-red-600 {{ request()->routeIs('vny.store') || request()->routeIs('vny.products.*') ? 'text-red-600 font-semibold' : '' }}">VNY Store</a>
                <a href="{{ route('vny.about') }}" class="block px-3 py-2 text-gray-700 hover:text-red-600 {{ request()->routeIs('vny.about') ? 'text-red-600 font-semibold' : '' }}">VNY About</a>
                <a href="{{ route('gallery') }}" class="block px-3 py-2 text-gray-700 hover:text-red-600 {{ request()->routeIs('gallery') ? 'text-red-600 font-semibold' : '' }}">Gallery</a>
                <a href="{{ route('about') }}" class="block px-3 py-2 text-gray-700 hover:text-red-600 {{ request()->routeIs('about') ? 'text-red-600 font-semibold' : '' }}">About</a>
                <a href="{{ route('transactions') }}" class="block px-3 py-2 text-gray-700 hover:text-red-600 {{ request()->routeIs('transactions') ? 'text-red-600 font-semibold' : '' }}">Transactions</a>
            </div>
        </div>
    </div>
</header>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-btn').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });
</script>