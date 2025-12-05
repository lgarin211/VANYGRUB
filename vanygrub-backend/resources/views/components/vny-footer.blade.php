{{-- VNY Footer Component --}}
@props([
    'showSocial' => true,
    'showNewsletter' => true
])

<footer class="vny-header-gradient text-white">
    <div class="max-w-7xl mx-auto px-5 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- VNY Brand Info -->
            <div class="col-span-1">
                <div class="mb-6">
                    <h3 class="text-2xl font-bold tracking-wide mb-4">VNY</h3>
                    <p class="text-white/80 text-sm leading-relaxed">
                        Premium lifestyle collection yang memadukan tradisi dengan modernitas.
                        Kualitas terbaik untuk gaya hidup yang berkelas.
                    </p>
                </div>
                @if($showSocial)
                <div class="flex space-x-4">
                    <a href="#" class="text-white/80 hover:text-white transition-colors duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-white/80 hover:text-white transition-colors duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-white/80 hover:text-white transition-colors duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-white/80 hover:text-white transition-colors duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                </div>
                @endif
            </div>

            <!-- Quick Links -->
            <div class="col-span-1">
                <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-white/80 hover:text-white transition-colors duration-200 text-sm">Home</a></li>
                    <li><a href="{{ route('vny.store') }}" class="text-white/80 hover:text-white transition-colors duration-200 text-sm">VNY Store</a></li>
                    <li><a href="{{ route('vny.about') }}" class="text-white/80 hover:text-white transition-colors duration-200 text-sm">About VNY</a></li>
                    <li><a href="#" class="text-white/80 hover:text-white transition-colors duration-200 text-sm">Products</a></li>
                    <li><a href="#" class="text-white/80 hover:text-white transition-colors duration-200 text-sm">Collections</a></li>
                </ul>
            </div>

            <!-- Categories -->
            <div class="col-span-1">
                <h4 class="text-lg font-semibold mb-4">Categories</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-white/80 hover:text-white transition-colors duration-200 text-sm">Traditional Fashion</a></li>
                    <li><a href="#" class="text-white/80 hover:text-white transition-colors duration-200 text-sm">Premium Footwear</a></li>
                    <li><a href="#" class="text-white/80 hover:text-white transition-colors duration-200 text-sm">Accessories</a></li>
                    <li><a href="#" class="text-white/80 hover:text-white transition-colors duration-200 text-sm">Lifestyle</a></li>
                    <li><a href="#" class="text-white/80 hover:text-white transition-colors duration-200 text-sm">Limited Edition</a></li>
                </ul>
            </div>

            <!-- Contact & Newsletter -->
            <div class="col-span-1">
                <h4 class="text-lg font-semibold mb-4">Stay Connected</h4>
                <div class="space-y-3 mb-4">
                    <div class="flex items-center text-sm text-white/80">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        +62 813-1587-1101
                    </div>
                    <div class="flex items-center text-sm text-white/80">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        info@vnygroup.com
                    </div>
                    <div class="flex items-center text-sm text-white/80">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Indonesia
                    </div>
                </div>

                @if($showNewsletter)
                <div class="mt-6">
                    <p class="text-sm text-white/80 mb-3">Get updates on new collections</p>
                    <div class="flex">
                        <input type="email" placeholder="Your email" class="flex-1 px-3 py-2 text-sm bg-white/10 border border-white/20 rounded-l-lg placeholder-white/50 text-white focus:outline-none focus:border-white/40">
                        <button class="px-4 py-2 text-sm font-medium bg-white text-gray-900 rounded-r-lg hover:bg-gray-100 transition-colors duration-200">
                            Subscribe
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="border-t border-white/20 mt-12 pt-8">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <p class="text-sm text-white/80">
                    &copy; {{ date('Y') }} VNY. All rights reserved.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-sm text-white/80 hover:text-white transition-colors duration-200">Privacy Policy</a>
                    <a href="#" class="text-sm text-white/80 hover:text-white transition-colors duration-200">Terms of Service</a>
                    <a href="#" class="text-sm text-white/80 hover:text-white transition-colors duration-200">Sitemap</a>
                </div>
            </div>
        </div>
    </div>
</footer>

{{-- Add VNY Header Gradient Styles --}}
<style>
.vny-header-gradient {
    background: linear-gradient(135deg, #8B0000 0%, #DC143C 100%);
}
</style>
