@extends('layouts.app')

@section('title', 'Tailwind Test - VNY Store')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Test -->
    <header class="vny-header-gradient text-white py-6">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold text-center">Tailwind CSS Integration Test</h1>
            <p class="text-center mt-2 text-gray-100">Testing VNY Store custom components</p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Button Tests -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-6 text-gray-800">Button Components</h2>
            <div class="flex flex-wrap gap-4">
                <button class="vny-btn">Primary Button</button>
                <button class="vny-btn-secondary">Secondary Button</button>
                <button class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                    Standard Tailwind Button
                </button>
            </div>
        </section>

        <!-- Card Tests -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-6 text-gray-800">Card Components</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="vny-card">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-3">VNY Card</h3>
                        <p class="text-gray-600 mb-4">This is a custom VNY card component using Tailwind utilities.</p>
                        <button class="vny-btn">Learn More</button>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <h3 class="text-xl font-semibold mb-3">Standard Card</h3>
                    <p class="text-gray-600 mb-4">This is a standard Tailwind card without custom components.</p>
                    <button class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                        Learn More
                    </button>
                </div>
                
                <div class="vny-card">
                    <div class="h-48 bg-gradient-to-br from-vny-red to-vny-dark-red"></div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-3">Product Card</h3>
                        <p class="text-gray-600 mb-4">Product card with VNY gradient background.</p>
                        <button class="vny-btn-secondary">View Product</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Form Tests -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-6 text-gray-800">Form Components</h2>
            <div class="max-w-md">
                <form class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">VNY Input</label>
                        <input type="text" class="vny-input" placeholder="Enter text here">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Standard Input</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Standard Tailwind input">
                    </div>
                    <button type="button" class="vny-btn w-full">Submit Form</button>
                </form>
            </div>
        </section>

        <!-- Color Palette Test -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-6 text-gray-800">VNY Color Palette</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="w-full h-16 bg-vny-red rounded-lg mb-2"></div>
                    <p class="text-sm font-medium">VNY Red</p>
                    <p class="text-xs text-gray-500">#8B0000</p>
                </div>
                <div class="text-center">
                    <div class="w-full h-16 bg-vny-dark-red rounded-lg mb-2"></div>
                    <p class="text-sm font-medium">VNY Dark Red</p>
                    <p class="text-xs text-gray-500">#DC143C</p>
                </div>
                <div class="text-center">
                    <div class="w-full h-16 bg-primary-500 rounded-lg mb-2"></div>
                    <p class="text-sm font-medium">Primary 500</p>
                    <p class="text-xs text-gray-500">#ef4444</p>
                </div>
                <div class="text-center">
                    <div class="w-full h-16 bg-gradient-to-r from-vny-red to-vny-dark-red rounded-lg mb-2"></div>
                    <p class="text-sm font-medium">VNY Gradient</p>
                    <p class="text-xs text-gray-500">red → dark-red</p>
                </div>
            </div>
        </section>

        <!-- Animation Tests -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-6 text-gray-800">Animation Tests</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="vny-card animate-fade-in">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2">Fade In</h3>
                        <p class="text-gray-600">Custom fade-in animation</p>
                    </div>
                </div>
                <div class="vny-card animate-slide-up" style="animation-delay: 0.2s;">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2">Slide Up</h3>
                        <p class="text-gray-600">Custom slide-up animation</p>
                    </div>
                </div>
                <div class="vny-card animate-slide-down" style="animation-delay: 0.4s;">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2">Slide Down</h3>
                        <p class="text-gray-600">Custom slide-down animation</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- JavaScript Test -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-6 text-gray-800">JavaScript Integration</h2>
            <div class="space-y-4">
                <button onclick="VNY.notify('Success notification!', 'success')" class="vny-btn mr-4">
                    Test Success Notification
                </button>
                <button onclick="VNY.notify('Error notification!', 'error')" class="vny-btn-secondary mr-4">
                    Test Error Notification
                </button>
                <button onclick="VNY.scrollTo('header')" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    Scroll to Top
                </button>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <h3 class="text-xl font-semibold mb-2">Tailwind CSS Successfully Integrated!</h3>
            <p class="text-gray-300">VNY Store is now powered by Tailwind CSS with custom components</p>
            <div class="mt-4">
                <span class="inline-block px-3 py-1 bg-green-500 text-white rounded-full text-sm mr-2">✅ Tailwind CSS</span>
                <span class="inline-block px-3 py-1 bg-blue-500 text-white rounded-full text-sm mr-2">✅ Custom Components</span>
                <span class="inline-block px-3 py-1 bg-purple-500 text-white rounded-full text-sm">✅ Vite Integration</span>
            </div>
        </div>
    </footer>
</div>
@endsection