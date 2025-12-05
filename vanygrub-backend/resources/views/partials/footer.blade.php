<!-- Footer Component -->
<footer class="text-white bg-gray-900">
    <div class="container px-4 py-12 mx-auto">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
            <!-- Company Info -->
            <div class="col-span-1">
                <div class="flex items-center mb-4">
                    <img src="{{ asset('images/logo-white.png') }}" alt="VANY GROUB" class="w-auto h-8">
                    <span class="ml-3 text-xl font-bold">VANY GROUB</span>
                </div>
                <p class="mb-4 text-sm text-gray-300">
                    Premium Lifestyle Collection - Discover premium lifestyle products from traditional fashion to modern hospitality services.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-300 transition-colors duration-200 hover:text-white">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-300 transition-colors duration-200 hover:text-white">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-gray-300 transition-colors duration-200 hover:text-white">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-300 transition-colors duration-200 hover:text-white">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-span-1">
                <h3 class="mb-4 text-lg font-semibold">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-gray-300 transition-colors duration-200 hover:text-white">Home</a></li>
                    <li><a href="{{ route('vny.store') }}" class="text-gray-300 transition-colors duration-200 hover:text-white">VNY Store</a></li>
                    <li><a href="{{ route('gallery') }}" class="text-gray-300 transition-colors duration-200 hover:text-white">Gallery</a></li>
                    <li><a href="{{ route('about') }}" class="text-gray-300 transition-colors duration-200 hover:text-white">About</a></li>
                    <li><a href="{{ route('transactions') }}" class="text-gray-300 transition-colors duration-200 hover:text-white">Transactions</a></li>
                    <li><a href="{{ route('sitemap.html') }}" class="text-gray-300 transition-colors duration-200 hover:text-white">Sitemap</a></li>
                </ul>
            </div>

            <!-- Categories -->
            <div class="col-span-1">
                <h3 class="mb-4 text-lg font-semibold">Categories</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-300 transition-colors duration-200 hover:text-white">Traditional Fashion</a></li>
                    <li><a href="#" class="text-gray-300 transition-colors duration-200 hover:text-white">Footwear</a></li>
                    <li><a href="#" class="text-gray-300 transition-colors duration-200 hover:text-white">Hospitality</a></li>
                    <li><a href="#" class="text-gray-300 transition-colors duration-200 hover:text-white">Beauty & Wellness</a></li>
                    <li><a href="#" class="text-gray-300 transition-colors duration-200 hover:text-white">Home & Living</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-span-1">
                <h3 class="mb-4 text-lg font-semibold">Contact Info</h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <i class="mr-3 text-red-500 fas fa-map-marker-alt"></i>
                        <span class="text-sm text-gray-300">Jakarta, Indonesia</span>
                    </div>
                    <div class="flex items-center">
                        <i class="mr-3 text-red-500 fas fa-phone"></i>
                        <span class="text-sm text-gray-300">+62 123 456 789</span>
                    </div>
                    <div class="flex items-center">
                        <i class="mr-3 text-red-500 fas fa-envelope"></i>
                        <span class="text-sm text-gray-300">info@vanygroup.id</span>
                    </div>
                    <div class="flex items-center">
                        <i class="mr-3 text-red-500 fas fa-clock"></i>
                        <span class="text-sm text-gray-300">Mon - Fri: 9:00 - 18:00</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="pt-8 mt-8 border-t border-gray-700">
            <div class="flex flex-col items-center justify-between md:flex-row">
                <p class="text-sm text-gray-300">
                    &copy; {{ date('Y') }} VANY GROUB. All rights reserved.
                </p>
                <div class="flex mt-4 space-x-6 md:mt-0">
                    <a href="#" class="text-sm text-gray-300 transition-colors duration-200 hover:text-white">Privacy Policy</a>
                    <a href="#" class="text-sm text-gray-300 transition-colors duration-200 hover:text-white">Terms of Service</a>
                    <a href="#" class="text-sm text-gray-300 transition-colors duration-200 hover:text-white">Cookie Policy</a>
                    <a href="{{ route('sitemap.html') }}" class="text-sm text-gray-300 transition-colors duration-200 hover:text-white">Sitemap</a>
                </div>
            </div>
        </div>
    </div>
</footer>
