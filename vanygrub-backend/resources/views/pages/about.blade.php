@extends('layouts.app')

@section('title', 'About VNY Toba Shoes - VANY GROUP')
@section('description', 'Memadukan Kearifan Budaya Batak dengan Inovasi Modern dalam Setiap Langkah - VNY Toba Shoes')

@section('content')
<!-- Page Header -->
<section class="relative py-20 bg-gradient-to-br from-amber-900 via-amber-800 to-amber-900 text-white overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-30"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-amber-900/20 to-transparent"></div>

    <!-- Traditional pattern overlay -->
    <div class="absolute inset-0 opacity-10">
        <div class="w-full h-full bg-repeat" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"60\" height=\"60\" viewBox=\"0 0 60 60\"><g fill=\"%23ffffff\" opacity=\"0.1\"><polygon points=\"30,0 45,15 30,30 15,15\"/><polygon points=\"30,30 45,45 30,60 15,45\"/></g></svg>')"></div>
    </div>

    <div class="container mx-auto px-4 text-center relative z-10">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-5xl md:text-7xl font-bold mb-6 bg-gradient-to-r from-amber-200 to-yellow-300 bg-clip-text text-transparent" data-aos="fade-up">
                VNY Toba Shoes
            </h1>
            <p class="text-xl md:text-2xl opacity-90 mb-4 font-light" data-aos="fade-up" data-aos-delay="100">
                Memadukan Kearifan Budaya Batak dengan Inovasi Modern dalam Setiap Langkah
            </p>
            <div class="w-24 h-1 bg-gradient-to-r from-amber-400 to-yellow-400 mx-auto" data-aos="fade-up" data-aos-delay="200"></div>
        </div>
    </div>
</section>

<!-- Sejarah VNY Toba Shoes -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6" data-aos="fade-up">
                Sejarah VNY Toba Shoes
            </h2>
            <div class="w-24 h-1 bg-gradient-to-r from-amber-600 to-yellow-500 mx-auto mb-8" data-aos="fade-up" data-aos-delay="100"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div data-aos="fade-right">
                <div class="mb-12">
                    <h3 class="text-2xl md:text-3xl font-bold text-amber-800 mb-6">Awal Mula Perjalanan</h3>
                    <p class="text-gray-700 text-lg mb-6 leading-relaxed">
                        VNY Toba Shoes lahir dari sebuah visi untuk melestarikan kekayaan budaya Batak melalui karya seni yang dapat dikenakan sehari-hari. Didirikan dengan semangat untuk menghidupkan kembali motif-motif tradisional Batak yang sarat makna dan filosofi.
                    </p>
                    <p class="text-gray-700 text-lg mb-8 leading-relaxed">
                        Berawal dari kecintaan terhadap warisan leluhur, kami percaya bahwa setiap motif Batak memiliki cerita yang patut dilestarikan. Dari sinilah lahir ide untuk menghadirkan sepatu dengan sentuhan motif Batak yang autentik namun tetap modern.
                    </p>
                </div>

                <!-- Statistics -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 p-6 rounded-xl text-center border border-amber-200">
                        <div class="text-3xl font-bold text-amber-700 mb-2">5+</div>
                        <div class="text-amber-600 font-medium">Tahun Perjalanan</div>
                    </div>
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 p-6 rounded-xl text-center border border-amber-200">
                        <div class="text-3xl font-bold text-amber-700 mb-2">500+</div>
                        <div class="text-amber-600 font-medium">Pelanggan Setia</div>
                    </div>
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 p-6 rounded-xl text-center border border-amber-200 col-span-2 md:col-span-1">
                        <div class="text-3xl font-bold text-amber-700 mb-2">20+</div>
                        <div class="text-amber-600 font-medium">Model Sepatu</div>
                    </div>
                </div>
            </div>

            <div data-aos="fade-left">
                <div class="relative">
                    <!-- Decorative background -->
                    <div class="absolute -inset-4 bg-gradient-to-br from-amber-100 to-yellow-100 rounded-3xl transform rotate-3"></div>
                    <div class="relative bg-white p-4 rounded-2xl shadow-2xl">
                        <img src="{{ asset('images/vny-toba-history.svg') }}" alt="VNY Toba Shoes History" class="w-full h-80 object-cover rounded-xl">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent rounded-xl"></div>
                    </div>

                    <!-- Floating badge -->
                    <div class="absolute -bottom-4 -left-4 bg-amber-600 text-white px-6 py-3 rounded-full font-bold shadow-lg">
                        <span class="text-sm">Est. 2019</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filosofi Motif Batak -->
<section class="py-20 bg-gradient-to-br from-amber-50 via-yellow-50 to-amber-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6" data-aos="fade-up">
                Filosofi Motif Batak
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8" data-aos="fade-up" data-aos-delay="100">
                Setiap motif yang kami gunakan memiliki makna dan cerita yang mendalam
            </p>
            <div class="w-24 h-1 bg-gradient-to-r from-amber-600 to-yellow-500 mx-auto" data-aos="fade-up" data-aos-delay="200"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
            <!-- Gorga Motif -->
            <div class="group" data-aos="fade-up" data-aos-delay="100">
                <div class="bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                    <div class="h-48 bg-gradient-to-br from-amber-400 to-amber-600 relative overflow-hidden">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-32 h-32 border-4 border-white/30 rounded-full flex items-center justify-center">
                                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center">
                                    <i class="fas fa-gem text-white text-3xl"></i>
                                </div>
                            </div>
                        </div>
                        <img src="{{ asset('images/motif-gorga.svg') }}" alt="Motif Gorga" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-amber-800 mb-4">Gorga</h3>
                        <p class="text-gray-700 leading-relaxed">
                            Motif tradisional yang melambangkan keindahan, kemakmuran, dan perlindungan. Setiap garis dan lengkungan dalam gorga memiliki makna spiritual yang mendalam.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Ulos Motif -->
            <div class="group" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                    <div class="h-48 bg-gradient-to-br from-red-500 to-red-700 relative overflow-hidden">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-32 h-32 border-4 border-white/30 rounded-full flex items-center justify-center">
                                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center">
                                    <i class="fas fa-heart text-white text-3xl"></i>
                                </div>
                            </div>
                        </div>
                        <img src="{{ asset('images/motif-ulos.svg') }}" alt="Motif Ulos" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-red-800 mb-4">Ulos</h3>
                        <p class="text-gray-700 leading-relaxed">
                            Simbol kehangatan, kasih sayang, dan persatuan. Motif ulos yang kami adaptasi dalam sepatu menghadirkan rasa nyaman dan kedekatan dalam setiap langkah.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Singa Motif -->
            <div class="group" data-aos="fade-up" data-aos-delay="300">
                <div class="bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                    <div class="h-48 bg-gradient-to-br from-orange-500 to-orange-700 relative overflow-hidden">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-32 h-32 border-4 border-white/30 rounded-full flex items-center justify-center">
                                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center">
                                    <i class="fas fa-crown text-white text-3xl"></i>
                                </div>
                            </div>
                        </div>
                        <img src="{{ asset('images/motif-singa.svg') }}" alt="Motif Singa" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-orange-800 mb-4">Singa</h3>
                        <p class="text-gray-700 leading-relaxed">
                            Melambangkan kekuatan, keberanian, dan kepemimpinan. Motif singa dalam desain sepatu kami memberikan energi positif dan kepercayaan diri bagi yang memakainya.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Philosophy Images -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8" data-aos="fade-up" data-aos-delay="400">
            <div class="relative group overflow-hidden rounded-2xl">
                <img src="{{ asset('images/craftsmanship-1.svg') }}" alt="Traditional Craftsmanship" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-6 left-6 text-white">
                    <h4 class="text-xl font-bold mb-2">Keahlian Tradisional</h4>
                    <p class="text-sm opacity-90">Setiap detail dibuat dengan teknik warisan leluhur</p>
                </div>
            </div>
            <div class="relative group overflow-hidden rounded-2xl">
                <img src="{{ asset('images/craftsmanship-2.svg') }}" alt="Modern Innovation" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-6 left-6 text-white">
                    <h4 class="text-xl font-bold mb-2">Inovasi Modern</h4>
                    <p class="text-sm opacity-90">Memadukan teknologi masa kini dengan tradisi</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kualitas & Kerajinan Tangan -->
<section class="py-20 bg-white relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-gradient-to-br from-amber-100 to-transparent rounded-full -translate-x-48 -translate-y-48 opacity-50"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-br from-yellow-100 to-transparent rounded-full translate-x-48 translate-y-48 opacity-50"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6" data-aos="fade-up">
                Kualitas & Kerajinan Tangan
            </h2>
            <p class="text-xl text-gray-600 max-w-4xl mx-auto mb-8" data-aos="fade-up" data-aos-delay="100">
                Setiap pasang sepatu VNY Toba Shoes dibuat dengan perhatian detail yang tinggi. Kami menggabungkan teknik pembuatan sepatu modern dengan sentuhan kerajinan tangan tradisional untuk menghadirkan produk yang berkualitas premium.
            </p>
            <div class="w-24 h-1 bg-gradient-to-r from-amber-600 to-yellow-500 mx-auto" data-aos="fade-up" data-aos-delay="200"></div>
        </div>

        <!-- Quality Features -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <!-- Bahan Premium -->
            <div class="group text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="relative mb-6">
                    <div class="bg-gradient-to-br from-amber-100 to-amber-200 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto group-hover:from-amber-200 group-hover:to-amber-300 transition-all duration-300 transform group-hover:scale-110">
                        <i class="fas fa-award text-amber-700 text-3xl"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-amber-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-star text-white text-xs"></i>
                    </div>
                </div>
                <h4 class="text-xl font-bold text-gray-800 mb-4">Bahan Premium</h4>
                <p class="text-gray-600 leading-relaxed">
                    Kulit asli pilihan dan material berkualitas tinggi untuk kenyamanan maksimal
                </p>
            </div>

            <!-- Motif Autentik -->
            <div class="group text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="relative mb-6">
                    <div class="bg-gradient-to-br from-red-100 to-red-200 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto group-hover:from-red-200 group-hover:to-red-300 transition-all duration-300 transform group-hover:scale-110">
                        <i class="fas fa-palette text-red-700 text-3xl"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-red-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-white text-xs"></i>
                    </div>
                </div>
                <h4 class="text-xl font-bold text-gray-800 mb-4">Motif Autentik</h4>
                <p class="text-gray-600 leading-relaxed">
                    Setiap motif Batak diaplikasikan dengan teknik khusus untuk mempertahankan keaslian
                </p>
            </div>

            <!-- Finishing Handmade -->
            <div class="group text-center" data-aos="fade-up" data-aos-delay="300">
                <div class="relative mb-6">
                    <div class="bg-gradient-to-br from-orange-100 to-orange-200 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto group-hover:from-orange-200 group-hover:to-orange-300 transition-all duration-300 transform group-hover:scale-110">
                        <i class="fas fa-hands text-orange-700 text-3xl"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-orange-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-heart text-white text-xs"></i>
                    </div>
                </div>
                <h4 class="text-xl font-bold text-gray-800 mb-4">Finishing Handmade</h4>
                <p class="text-gray-600 leading-relaxed">
                    Setiap detail diselesaikan dengan tangan untuk hasil yang sempurna
                </p>
            </div>
        </div>

        <!-- Vision & Mission -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Visi -->
            <div data-aos="fade-right">
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 p-8 rounded-3xl border border-amber-200 h-full">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-amber-600 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-eye text-white text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-amber-800">Visi Kami</h3>
                    </div>
                    <p class="text-gray-700 text-lg leading-relaxed">
                        Menjadi brand sepatu terdepan yang membanggakan warisan budaya Batak melalui inovasi desain yang mendunia, sambil tetap menjaga keaslian dan makna filosofis setiap motif tradisional.
                    </p>
                    <div class="mt-6">
                        <img src="{{ asset('images/vision-image.svg') }}" alt="Visi VNY Toba Shoes" class="w-full h-32 object-cover rounded-xl">
                    </div>
                </div>
            </div>

            <!-- Misi -->
            <div data-aos="fade-left">
                <div class="bg-gradient-to-br from-red-50 to-red-100 p-8 rounded-3xl border border-red-200 h-full">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-bullseye text-white text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-red-800">Misi Kami</h3>
                    </div>
                    <p class="text-gray-700 text-lg leading-relaxed">
                        Menciptakan sepatu berkualitas tinggi dengan motif Batak autentik, mengedukasi masyarakat tentang kekayaan budaya Batak, dan memberikan kontribusi positif bagi pelestarian warisan budaya Indonesia.
                    </p>
                    <div class="mt-6">
                        <img src="{{ asset('images/mission-image.svg') }}" alt="Misi VNY Toba Shoes" class="w-full h-32 object-cover rounded-xl">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bergabunglah dalam Melestarikan Budaya Batak -->
<section class="py-20 bg-gradient-to-br from-amber-900 via-amber-800 to-amber-900 text-white relative overflow-hidden">
    <!-- Decorative patterns -->
    <div class="absolute inset-0 opacity-10">
        <div class="w-full h-full bg-repeat" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"80\" height=\"80\" viewBox=\"0 0 80 80\"><g fill=\"%23ffffff\" opacity=\"0.1\"><circle cx=\"40\" cy=\"40\" r=\"20\"/><path d=\"M20 20L60 60M60 20L20 60\"/></g></svg>')"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-bold mb-6" data-aos="fade-up">
                Bergabunglah dalam Melestarikan Budaya Batak
            </h2>
            <p class="text-xl md:text-2xl opacity-90 mb-8 max-w-4xl mx-auto font-light" data-aos="fade-up" data-aos-delay="100">
                Setiap pembelian sepatu VNY Toba Shoes adalah langkah nyata Anda dalam mendukung pelestarian budaya Batak yang kaya dan bermakna.
            </p>
            <div class="w-24 h-1 bg-gradient-to-r from-amber-400 to-yellow-400 mx-auto mb-12" data-aos="fade-up" data-aos-delay="200"></div>
        </div>

        <!-- Call to Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-6 justify-center mb-16" data-aos="fade-up" data-aos-delay="300">
            <a href="{{ route('vny.products') }}" class="bg-gradient-to-r from-amber-500 to-yellow-500 text-amber-900 px-10 py-4 rounded-full font-bold text-lg hover:from-amber-400 hover:to-yellow-400 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center group">
                <i class="fas fa-shopping-bag mr-3 group-hover:animate-bounce"></i>
                🛍️ Lihat Koleksi
            </a>
            <a href="{{ route('vny.store') }}" class="border-2 border-amber-400 text-amber-100 px-10 py-4 rounded-full font-bold text-lg hover:bg-amber-400 hover:text-amber-900 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center group">
                <i class="fas fa-home mr-3 group-hover:animate-bounce"></i>
                🏠 Kembali ke VNY Store
            </a>
        </div>

        <!-- VNY GROUP Info -->
        <div class="bg-black/20 backdrop-blur-sm rounded-3xl p-8 max-w-4xl mx-auto" data-aos="fade-up" data-aos-delay="400">
            <div class="text-center mb-8">
                <h3 class="text-3xl font-bold mb-4 text-amber-200">VNY GROUP</h3>
                <p class="text-lg opacity-90 mb-6">
                    Membanggakan warisan budaya Indonesia melalui produk berkualitas tinggi dan inovasi yang berkelanjutan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <!-- Quick Links -->
                <div>
                    <h4 class="text-xl font-bold mb-4 text-amber-300">Quick Links</h4>
                    <div class="space-y-2">
                        <a href="{{ route('home') }}" class="block text-amber-100 hover:text-amber-300 transition-colors duration-200">Home</a>
                        <a href="{{ route('vny.store') }}" class="block text-amber-100 hover:text-amber-300 transition-colors duration-200">VNY Store</a>
                        <a href="{{ route('about') }}" class="block text-amber-300 font-semibold">About</a>
                        <a href="{{ route('gallery') }}" class="block text-amber-100 hover:text-amber-300 transition-colors duration-200">Gallery</a>
                    </div>
                </div>

                <!-- Products -->
                <div>
                    <h4 class="text-xl font-bold mb-4 text-amber-300">Products</h4>
                    <div class="space-y-2">
                        <a href="{{ route('vny.products') }}" class="block text-amber-100 hover:text-amber-300 transition-colors duration-200">All Products</a>
                        <a href="{{ route('vny.products') }}?category=sneakers" class="block text-amber-100 hover:text-amber-300 transition-colors duration-200">Sneakers</a>
                        <a href="{{ route('vny.products') }}?category=casual" class="block text-amber-100 hover:text-amber-300 transition-colors duration-200">Casual</a>
                        <a href="{{ route('vny.products') }}?category=traditional" class="block text-amber-100 hover:text-amber-300 transition-colors duration-200">Traditional</a>
                    </div>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-xl font-bold mb-4 text-amber-300">Contact</h4>
                    <div class="space-y-2 text-amber-100">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-envelope mr-2"></i>
                            <span class="text-sm">info@vnygroup.com</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <i class="fas fa-phone mr-2"></i>
                            <span class="text-sm">+62 813-1587-1101</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span class="text-sm">Medan, North Sumatra</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-amber-600/30 mt-8 pt-6 text-center">
                <p class="text-amber-200 text-sm">
                    © {{ date('Y') }} VNY Group. All rights reserved. | Proudly preserving Batak culture through innovation.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
