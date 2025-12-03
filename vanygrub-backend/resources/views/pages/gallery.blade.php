@extends('layouts.app')

@section('title', 'Gallery - VANY GROUB')
@section('description', 'Browse our product gallery and collections')

@section('content')
<!-- Page Header -->
<section class="py-16 text-white bg-gradient-to-br from-red-600 to-red-800">
    <div class="container px-4 mx-auto text-center">
        <h1 class="mb-4 text-4xl font-bold md:text-5xl" data-aos="fade-up">Gallery</h1>
        <p class="max-w-2xl mx-auto text-xl opacity-90" data-aos="fade-up" data-aos-delay="100">
            Explore our stunning collection of premium products and lifestyle moments
        </p>
    </div>
</section>

<!-- Gallery Grid -->
<section class="py-16 bg-gray-50">
    <div class="container px-4 mx-auto">
        <!-- Category Filter -->
        @if($categories->count() > 0)
        <div class="flex flex-wrap justify-center gap-4 mb-12">
            <button class="px-6 py-2 text-white transition-colors duration-200 bg-red-600 rounded-full gallery-filter active hover:bg-red-700" data-filter="all">
                All Categories
            </button>
            @foreach($categories as $category)
            <button class="px-6 py-2 text-gray-700 transition-colors duration-200 bg-white border border-gray-300 rounded-full gallery-filter hover:bg-gray-100" data-filter="{{ $category->slug }}">
                {{ $category->name }}
            </button>
            @endforeach
        </div>
        @endif

        <!-- Gallery Items -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" id="gallery-grid">
            @if($products->count() > 0)
                @foreach($products as $product)
                <div class="cursor-pointer gallery-item group" data-category="{{ $product->category->slug ?? 'uncategorized' }}" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                    <div class="overflow-hidden transition-all duration-300 transform bg-white shadow-lg rounded-xl hover:scale-105">
                        <div class="relative h-64 bg-gray-200">
                            @if($product->image)
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110">
                            @elseif($product->mainImage)
                            <img src="{{ $product->mainImage }}" alt="{{ $product->name }}" class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110">
                            @endif

                            <div class="absolute inset-0 transition-all duration-300 bg-black bg-opacity-0 group-hover:bg-opacity-30"></div>

                            <!-- Overlay Info -->
                            <div class="absolute inset-0 flex items-center justify-center transition-all duration-300 opacity-0 group-hover:opacity-100">
                                <div class="text-center text-white">
                                    <h3 class="mb-2 text-lg font-bold">{{ $product->name }}</h3>
                                    <p class="text-sm opacity-90">{{ $product->category->name ?? 'Uncategorized' }}</p>
                                    <div class="mt-4">
                                        <button class="px-4 py-2 text-gray-800 transition-colors duration-200 bg-white rounded-lg hover:bg-gray-100 gallery-view-btn" data-image="{{ $product->image ?: $product->mainImage }}" data-title="{{ $product->name }}" data-description="{{ $product->short_description }}">
                                            <i class="mr-2 fas fa-eye"></i> View
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4">
                            <h3 class="mb-1 font-semibold text-gray-800">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $product->category->name ?? 'Uncategorized' }}</p>
                            @if($product->price)
                            <p class="mt-2 font-semibold text-red-600">
                                @if($product->sale_price)
                                    Rp {{ number_format($product->sale_price, 0, ',', '.') }}
                                    <span class="ml-2 text-sm text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                @else
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                @endif
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="py-12 text-center col-span-full">
                    <div class="mb-4 text-6xl text-gray-400">
                        <i class="fas fa-images"></i>
                    </div>
                    <h3 class="mb-2 text-xl font-semibold text-gray-600">No Gallery Items</h3>
                    <p class="text-gray-500">Gallery items will appear here when products are added</p>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-75">
    <div class="relative max-w-4xl max-h-full p-4">
        <button id="closeModal" class="absolute z-10 text-3xl text-white top-2 right-2 hover:text-gray-300">
            <i class="fas fa-times"></i>
        </button>
        <div class="overflow-hidden bg-white rounded-lg">
            <img id="modalImage" src="" alt="" class="object-contain w-full h-auto max-h-96">
            <div class="p-6">
                <h3 id="modalTitle" class="mb-2 text-2xl font-bold text-gray-800"></h3>
                <p id="modalDescription" class="text-gray-600"></p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Gallery Filter
const filterButtons = document.querySelectorAll('.gallery-filter');
const galleryItems = document.querySelectorAll('.gallery-item');

filterButtons.forEach(button => {
    button.addEventListener('click', () => {
        const filter = button.getAttribute('data-filter');

        // Update active button
        filterButtons.forEach(btn => {
            btn.classList.remove('active', 'bg-red-600', 'text-white');
            btn.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-300');
        });
        button.classList.add('active', 'bg-red-600', 'text-white');
        button.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-300');

        // Filter items
        galleryItems.forEach(item => {
            const itemCategory = item.getAttribute('data-category');
            if (filter === 'all' || itemCategory === filter) {
                item.style.display = 'block';
                item.style.animation = 'fadeIn 0.5s ease-in-out';
            } else {
                item.style.display = 'none';
            }
        });
    });
});

// Image Modal
const modal = document.getElementById('imageModal');
const modalImage = document.getElementById('modalImage');
const modalTitle = document.getElementById('modalTitle');
const modalDescription = document.getElementById('modalDescription');
const closeModal = document.getElementById('closeModal');

document.querySelectorAll('.gallery-view-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const image = btn.getAttribute('data-image');
        const title = btn.getAttribute('data-title');
        const description = btn.getAttribute('data-description');

        modalImage.src = image;
        modalTitle.textContent = title;
        modalDescription.textContent = description || 'No description available';
        modal.classList.remove('hidden');
    });
});

closeModal.addEventListener('click', () => {
    modal.classList.add('hidden');
});

modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.classList.add('hidden');
    }
});

// Keyboard navigation
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
        modal.classList.add('hidden');
    }
});
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endsection
