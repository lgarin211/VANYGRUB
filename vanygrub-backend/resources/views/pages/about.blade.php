@extends('layouts.app')

@section('title', 'About Us - VANY GROUB')
@section('description', 'Learn more about VANY GROUB, our mission, vision, and premium lifestyle collection')

@section('content')
<!-- Page Header -->
<section class="py-16 bg-gradient-to-br from-red-600 to-red-800 text-white">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4" data-aos="fade-up">About VANY GROUB</h1>
        <p class="text-xl opacity-90 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Discover the story behind our premium lifestyle collection and commitment to excellence
        </p>
    </div>
</section>

<!-- Company Story -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div data-aos="fade-right">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6">Our Story</h2>
                <p class="text-gray-600 text-lg mb-6 leading-relaxed">
                    VANY GROUB was founded with a vision to bring premium lifestyle products to discerning customers who appreciate quality, craftsmanship, and style. Our journey began with traditional fashion and has evolved to encompass a diverse range of lifestyle products.
                </p>
                <p class="text-gray-600 text-lg mb-6 leading-relaxed">
                    From traditional Batak motifs to modern hospitality services, we curate collections that reflect our commitment to cultural heritage and contemporary excellence. Every product in our catalog tells a story of passion, dedication, and uncompromising quality.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="bg-red-50 p-6 rounded-lg text-center">
                        <div class="text-3xl font-bold text-red-600 mb-2">10+</div>
                        <div class="text-gray-600">Years Experience</div>
                    </div>
                    <div class="bg-red-50 p-6 rounded-lg text-center">
                        <div class="text-3xl font-bold text-red-600 mb-2">1000+</div>
                        <div class="text-gray-600">Happy Customers</div>
                    </div>
                    <div class="bg-red-50 p-6 rounded-lg text-center">
                        <div class="text-3xl font-bold text-red-600 mb-2">50+</div>
                        <div class="text-gray-600">Premium Products</div>
                    </div>
                </div>
            </div>
            <div data-aos="fade-left">
                <div class="bg-gradient-to-br from-red-100 to-red-200 p-8 rounded-2xl">
                    <img src="{{ asset('images/about-us.jpg') }}" alt="About VANY GROUB" class="w-full h-80 object-cover rounded-xl shadow-lg">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4" data-aos="fade-up">Mission & Vision</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                Our commitment to excellence drives everything we do
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-xl shadow-lg" data-aos="fade-up" data-aos-delay="200">
                <div class="text-red-600 text-4xl mb-4">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Our Mission</h3>
                <p class="text-gray-600 leading-relaxed">
                    To provide exceptional premium lifestyle products that combine traditional craftsmanship with modern innovation,
                    delivering unparalleled quality and style to customers who value excellence and authenticity in their lifestyle choices.
                </p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-lg" data-aos="fade-up" data-aos-delay="300">
                <div class="text-red-600 text-4xl mb-4">
                    <i class="fas fa-eye"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Our Vision</h3>
                <p class="text-gray-600 leading-relaxed">
                    To become the leading premium lifestyle brand in Southeast Asia, known for our commitment to cultural heritage,
                    innovative design, and exceptional customer experience while fostering sustainable and ethical business practices.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Our Values -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4" data-aos="fade-up">Our Values</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                The principles that guide our every decision and action
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="text-center group" data-aos="fade-up" data-aos-delay="100">
                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-red-200 transition-colors duration-200">
                    <i class="fas fa-gem text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Quality</h3>
                <p class="text-gray-600">We never compromise on quality and ensure every product meets our high standards</p>
            </div>

            <div class="text-center group" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-red-200 transition-colors duration-200">
                    <i class="fas fa-heart text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Authenticity</h3>
                <p class="text-gray-600">Our products reflect genuine cultural heritage and authentic craftsmanship</p>
            </div>

            <div class="text-center group" data-aos="fade-up" data-aos-delay="300">
                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-red-200 transition-colors duration-200">
                    <i class="fas fa-lightbulb text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Innovation</h3>
                <p class="text-gray-600">We continuously innovate to create products that blend tradition with modernity</p>
            </div>

            <div class="text-center group" data-aos="fade-up" data-aos-delay="400">
                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-red-200 transition-colors duration-200">
                    <i class="fas fa-users text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Community</h3>
                <p class="text-gray-600">We support local artisans and communities while building lasting relationships</p>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4" data-aos="fade-up">Our Team</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                Meet the passionate individuals behind VANY GROUB
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-xl shadow-lg text-center group hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                <div class="w-24 h-24 bg-gradient-to-br from-red-400 to-red-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <span class="text-white text-2xl font-bold">VG</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Founder & CEO</h3>
                <p class="text-red-600 mb-3">Vision & Strategy</p>
                <p class="text-gray-600 text-sm">Leading the company with passion for premium lifestyle products and cultural heritage preservation.</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg text-center group hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                <div class="w-24 h-24 bg-gradient-to-br from-red-400 to-red-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <span class="text-white text-2xl font-bold">CM</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Creative Director</h3>
                <p class="text-red-600 mb-3">Design & Innovation</p>
                <p class="text-gray-600 text-sm">Overseeing product design and ensuring our collections maintain the perfect balance of tradition and modernity.</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg text-center group hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="300">
                <div class="w-24 h-24 bg-gradient-to-br from-red-400 to-red-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <span class="text-white text-2xl font-bold">OM</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Operations Manager</h3>
                <p class="text-red-600 mb-3">Quality & Excellence</p>
                <p class="text-gray-600 text-sm">Ensuring smooth operations and maintaining our high standards of quality across all product lines.</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact CTA -->
<section class="py-16 bg-gradient-to-br from-red-600 to-red-800 text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4" data-aos="fade-up">Ready to Experience Premium Quality?</h2>
        <p class="text-xl opacity-90 mb-8 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Discover our premium lifestyle collection and join thousands of satisfied customers
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="200">
            <a href="{{ route('vny.store') }}" class="bg-white text-red-600 px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition-colors duration-200">
                Explore Products
            </a>
            <a href="{{ route('home') }}#contact" class="border-2 border-white text-white px-8 py-4 rounded-full font-semibold hover:bg-white hover:text-red-600 transition-colors duration-200">
                Contact Us
            </a>
        </div>
    </div>
</section>
@endsection
