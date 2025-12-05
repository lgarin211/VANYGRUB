@extends('layouts.app')

@section('title', 'VNY Store - Premium Sneakers Collection')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
<style>
/* Custom Owl Carousel styling for VNY Store */
#heroOwlCarousel {
  height: 600px !important;
  min-height: 400px !important;
}

#heroOwlCarousel .owl-stage-outer {
  height: 100% !important;
}

#heroOwlCarousel .owl-stage {
  height: 100% !important;
  display: flex !important;
}

#heroOwlCarousel .owl-item {
  height: 100% !important;
  display: flex !important;
}

.hero-slide {
  width: 100% !important;
  height: 100% !important;
  min-height: 400px !important;
  display: flex !important;
  align-items: center !important;
  justify-content: space-between !important;
  padding: 40px 5% !important;
  color: white !important;
  position: relative !important;
  background: linear-gradient(135deg, #8B0000 0%, #DC143C 100%) !important;
}

.hero-content {
  flex: 1 !important;
  max-width: 500px !important;
  z-index: 2 !important;
  padding: 20px !important;
}

.hero-content h1 {
  font-size: clamp(2rem, 5vw, 4.5rem) !important;
  font-weight: 800 !important;
  margin-bottom: 1rem !important;
  line-height: 1.1 !important;
  text-transform: uppercase !important;
  color: white !important;
}

.hero-content .subtitle {
  font-size: 1.1rem !important;
  font-weight: 400 !important;
  margin-bottom: 0.5rem !important;
  opacity: 0.9 !important;
  text-transform: uppercase !important;
  color: white !important;
}

.hero-price {
  font-size: 2.5rem !important;
  font-weight: 700 !important;
  margin: 1.5rem 0 !important;
  color: white !important;
}

.hero-btn {
  display: inline-block !important;
  padding: 12px 24px !important;
  background: white !important;
  color: #333 !important;
  font-weight: 600 !important;
  text-decoration: none !important;
  border-radius: 25px !important;
  transition: all 0.3s ease !important;
  text-transform: uppercase !important;
  letter-spacing: 1px !important;
}

.hero-btn:hover {
  background: #f0f0f0 !important;
  transform: translateY(-2px) !important;
}

.hero-image {
  flex: 1 !important;
  display: flex !important;
  justify-content: center !important;
  align-items: center !important;
  padding: 20px !important;
}

.hero-image img {
  max-width: 350px !important;
  max-height: 350px !important;
  object-fit: contain !important;
  filter: drop-shadow(0 15px 40px rgba(0,0,0,0.4)) !important;
}

.owl-nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 100%;
  display: flex;
  justify-content: space-between;
  padding: 0 2rem;
  z-index: 10;
}

.owl-nav button {
  width: 50px !important;
  height: 50px !important;
  background: rgba(255,255,255,0.2) !important;
  border: 2px solid rgba(255,255,255,0.3) !important;
  color: white !important;
  font-size: 20px !important;
  border-radius: 50% !important;
  transition: all 0.3s ease !important;
}

.owl-nav button:hover {
  background: rgba(255,255,255,0.3) !important;
  transform: scale(1.1) !important;
}

.owl-dots {
  position: absolute;
  bottom: 2rem;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 12px;
  z-index: 10;
}

.owl-dot {
  width: 12px !important;
  height: 12px !important;
  border-radius: 50% !important;
  background: rgba(255,255,255,0.4) !important;
  transition: all 0.3s ease;
}

.owl-dot.active {
  background: white !important;
  transform: scale(1.3);
}

@media (max-width: 768px) {
  .hero-slide {
    flex-direction: column;
    text-align: center;
    padding: 2rem;
    justify-content: center;
  }

  .hero-content {
    max-width: 100%;
    margin-bottom: 2rem;
  }

  .hero-content h1 {
    font-size: 2rem;
  }

  .hero-price {
    font-size: 1.5rem;
  }

  .hero-image img {
    max-width: 250px;
    max-height: 250px;
  }
}
</style>
@endsection

@section('content')
<!-- Header -->
@include('components.vny-navbar', ['currentPage' => 'gallery'])

<!-- Breadcrumb -->
<div class="border-b border-gray-200 bg-gray-50">
  <div class="px-5 py-3 mx-auto max-w-7xl">
    <nav class="flex items-center space-x-2 text-sm">
      <a href="{{ route('home') }}" class="font-medium transition-colors duration-200 text-vny-red hover:text-vny-dark-red">Vany Group</a>
      <span class="text-gray-400">/</span>
      <span class="font-semibold text-gray-700">VNY Store</span>
    </nav>
  </div>
</div>

<!-- Hero Slider Section -->
<section class="relative h-96 md:h-[600px] vny-header-gradient overflow-hidden">
  <div class="h-full owl-carousel owl-theme" id="heroOwlCarousel">
    <!-- Default slide sebelum JavaScript load -->
    <div class="hero-slide" style="display: flex !important; align-items: center !important; justify-content: space-between !important; padding: 40px 5% !important; height: 100% !important; background: linear-gradient(135deg, #8B0000 0%, #DC143C 100%) !important;">
      <div class="hero-content" style="flex: 1 !important; color: white !important; padding: 20px !important;">
        <h1 style="font-size: 3rem !important; font-weight: 800 !important; margin-bottom: 1rem !important; color: white !important; text-transform: uppercase !important;">VNY Store</h1>
        <div class="subtitle" style="font-size: 1.1rem !important; margin-bottom: 0.5rem !important; color: white !important; opacity: 0.9 !important; text-transform: uppercase !important;">Premium Collection</div>
        <p style="color: white !important; opacity: 0.8 !important; margin-bottom: 1.5rem !important;">Koleksi premium dengan kualitas terbaik</p>
        <div class="hero-price" style="font-size: 2.5rem !important; font-weight: 700 !important; margin: 1.5rem 0 !important; color: white !important;">Rp. 450.000</div>
        <a href="#" class="hero-btn" style="display: inline-block !important; padding: 12px 24px !important; background: white !important; color: #333 !important; font-weight: 600 !important; text-decoration: none !important; border-radius: 25px !important;">Shop Now</a>
      </div>
      <div class="hero-image" style="flex: 1 !important; display: flex !important; justify-content: center !important; align-items: center !important; padding: 20px !important;">
        <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?w=350&h=350&fit=crop&crop=center" alt="VNY Product" style="max-width: 350px !important; max-height: 350px !important; object-fit: contain !important; filter: drop-shadow(0 15px 40px rgba(0,0,0,0.4)) !important;">
      </div>
    </div>
  </div>
</section>

<!-- Kategori Unggulan Section -->
<section class="py-16 bg-white">
  <div class="px-5 mx-auto max-w-7xl">
    <div class="mb-12 text-center">
      <h2 class="mb-4 text-3xl font-bold text-gray-900 md:text-4xl">Kategori Unggulan</h2>
      <p class="max-w-2xl mx-auto text-lg text-gray-600">Jelajahi berbagai kategori produk pilihan dari VNY Store</p>
    </div>
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 md:gap-8" id="categoriesGrid">
      <!-- Categories will be loaded here via JavaScript -->
      <div class="relative overflow-hidden transition-all duration-300 bg-white rounded-lg cursor-pointer group hover:shadow-xl" onclick="showCategoryDetail(101)">
        <div class="aspect-[4/3] overflow-hidden bg-gray-100 animate-pulse">
          <div class="flex items-center justify-center w-full h-full bg-gray-200">
            <span class="text-sm text-gray-500">Loading...</span>
          </div>
        </div>
        <div class="p-6">
          <div class="h-5 mb-3 bg-gray-200 rounded animate-pulse"></div>
          <div class="h-3 mb-1 bg-gray-200 rounded animate-pulse"></div>
          <div class="w-3/4 h-3 mb-4 bg-gray-200 rounded animate-pulse"></div>
          <div class="h-10 bg-gray-200 rounded animate-pulse"></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- News Product Section -->
<section class="py-16 bg-gray-50">
  <div class="px-5 mx-auto max-w-7xl">
    <div class="mb-12 text-center">
      <h2 class="mb-4 text-3xl font-bold text-gray-900 md:text-4xl">News Product</h2>
      <p class="max-w-2xl mx-auto text-lg text-gray-600">Produk terbaru dari koleksi VNY Store</p>
    </div>
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 md:gap-8" id="newsGrid">
      <!-- Loading state for News products dengan layout baru -->
      <div class="relative overflow-hidden bg-white rounded-xl animate-pulse">
        <div class="relative">
          <div class="w-full h-64 bg-gray-200"></div>
          <div class="absolute top-0 left-0 w-16 h-6 bg-gray-300 rounded-br-lg"></div>
        </div>
        <div class="p-6 space-y-4">
          <div class="h-6 bg-gray-200 rounded"></div>
          <div class="flex items-start space-x-2">
            <div class="w-4 h-4 mt-1 bg-gray-200 rounded"></div>
            <div class="flex-1 space-y-2">
              <div class="h-4 bg-gray-200 rounded"></div>
              <div class="w-3/4 h-4 bg-gray-200 rounded"></div>
            </div>
          </div>
          <div class="w-24 h-6 bg-gray-200 rounded"></div>
          <div class="w-full h-12 bg-gray-200 rounded-lg"></div>
        </div>
      </div>
      <div class="relative overflow-hidden bg-white rounded-xl animate-pulse">
        <div class="relative">
          <div class="w-full h-64 bg-gray-200"></div>
          <div class="absolute top-0 left-0 w-16 h-6 bg-gray-300 rounded-br-lg"></div>
        </div>
        <div class="p-6 space-y-4">
          <div class="h-6 bg-gray-200 rounded"></div>
          <div class="flex items-start space-x-2">
            <div class="w-4 h-4 mt-1 bg-gray-200 rounded"></div>
            <div class="flex-1 space-y-2">
              <div class="h-4 bg-gray-200 rounded"></div>
              <div class="w-3/4 h-4 bg-gray-200 rounded"></div>
            </div>
          </div>
          <div class="w-24 h-6 bg-gray-200 rounded"></div>
          <div class="w-full h-12 bg-gray-200 rounded-lg"></div>
        </div>
      </div>
      <div class="relative overflow-hidden bg-white rounded-xl animate-pulse">
        <div class="relative">
          <div class="w-full h-64 bg-gray-200"></div>
          <div class="absolute top-0 left-0 w-16 h-6 bg-gray-300 rounded-br-lg"></div>
        </div>
        <div class="p-6 space-y-4">
          <div class="h-6 bg-gray-200 rounded"></div>
          <div class="flex items-start space-x-2">
            <div class="w-4 h-4 mt-1 bg-gray-200 rounded"></div>
            <div class="flex-1 space-y-2">
              <div class="h-4 bg-gray-200 rounded"></div>
              <div class="w-3/4 h-4 bg-gray-200 rounded"></div>
            </div>
          </div>
          <div class="w-24 h-6 bg-gray-200 rounded"></div>
          <div class="w-full h-12 bg-gray-200 rounded-lg"></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Our Collection Section -->
<section class="py-16 bg-white">
  <div class="px-5 mx-auto max-w-7xl">
    <div class="mb-12 text-center">
      <h2 class="mb-4 text-3xl font-bold text-gray-900 md:text-4xl">Our Collection</h2>
      <p class="max-w-2xl mx-auto text-lg text-gray-600">Koleksi terbaik kami untuk Anda</p>
    </div>
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" id="collectionGrid">
      <!-- Collection will be loaded here via JavaScript -->
    </div>
  </div>
</section>

<!-- Special Offer Section -->
<section class="py-16 vny-header-gradient">
  <div class="px-5 mx-auto max-w-7xl">
    <div class="text-center text-white" id="specialOfferContent">
      <!-- Special offer will be loaded here via JavaScript -->
    </div>
  </div>
</section>

<!-- Product Detail Modal -->
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

<!-- Collection Cinematic Modal -->
<div id="collectionModal" class="fixed inset-0 z-50 hidden overflow-hidden">
  <div class="relative w-full h-full">
    <!-- Background Image -->
    <div class="absolute inset-0">
      <img id="collectionModalBg" src="" alt="" class="object-cover w-full h-full" style="filter: brightness(0.5);object-position: bottom">
      <div class="absolute inset-0 bg-black bg-opacity-20"></div>
    </div>

    <!-- Close Button -->
    <button id="collectionModalCloseBtn" onclick="closeCollectionModal()" class="absolute z-10 p-2 text-white transition-colors duration-200 top-6 right-6 hover:text-gray-300">
      <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
      </svg>
    </button>

    <!-- Content -->
    <div class="relative z-10 flex items-center h-full">
      <div class="w-full max-w-4xl px-8 mx-auto">
        <div class="text-white">
          <!-- Main Title -->
          <h1 id="collectionModalTitle" class="mb-4 text-5xl font-bold leading-tight md:text-7xl drop-shadow-2xl">Brand Motion</h1>

          <!-- Subtitle -->
          <div class="mb-6 text-lg text-green-400 md:text-xl drop-shadow-lg" id="collectionModalSubtitle">Identity in motion</div>

          <!-- Description -->
          <div class="max-w-xl mb-8">
            <p id="collectionModalDescription" class="text-lg leading-relaxed text-gray-300 md:text-xl drop-shadow-md">
              Translate marks into kinetic systems. Timing, easing, and restraint create memory.
            </p>
          </div>

          <!-- Action Button -->
          <div class="flex space-x-4">
            <button class="px-8 py-3 text-black transition-all duration-300 bg-green-400 rounded-full hover:bg-green-300 hover:shadow-lg drop-shadow-lg">
              <span class="font-medium">Explore reels</span>
            </button>
            <button id="collectionModalCloseBtn2" onclick="closeCollectionModal()" class="px-8 py-3 text-white transition-all duration-300 border border-white rounded-full hover:bg-white hover:text-black">
              <span class="font-medium">Close</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let vnyData = [];
    let siteConfig = {};

    // API Notifications
    const apiErrorNotification = document.createElement('div');
    apiErrorNotification.id = 'apiErrorNotification';
    apiErrorNotification.className = 'fixed z-50 px-4 py-2 text-white bg-red-600 rounded-lg shadow-lg top-4 right-4';
    apiErrorNotification.style.display = 'none';
    apiErrorNotification.innerHTML = `
        <div class="flex items-center space-x-2">
            <span>⚠️</span>
            <span class="text-sm">API offline - Using fallback data</span>
        </div>
    `;
    document.body.appendChild(apiErrorNotification);

    const apiSuccessNotification = document.createElement('div');
    apiSuccessNotification.id = 'apiSuccessNotification';
    apiSuccessNotification.className = 'fixed z-50 px-4 py-2 text-white bg-green-600 rounded-lg shadow-lg top-4 right-4';
    apiSuccessNotification.style.display = 'none';
    apiSuccessNotification.innerHTML = `
        <div class="flex items-center space-x-2">
            <span>✅</span>
            <span class="text-sm">Connected to VNY API</span>
        </div>
    `;
    document.body.appendChild(apiSuccessNotification);

    // Show loading state
    const loadingIndicator = document.createElement('div');
    loadingIndicator.id = 'loadingIndicator';
    loadingIndicator.className = 'fixed z-50 px-4 py-2 text-white bg-blue-600 rounded-lg shadow-lg top-4 right-4';
    loadingIndicator.innerHTML = `
        <div class="flex items-center space-x-2">
            <span>🔄</span>
            <span class="text-sm">Loading VNY data...</span>
        </div>
    `;
    document.body.appendChild(loadingIndicator);

    // Owl Carousel will handle slide management

    // Load data from APIs with timeout
    const fetchWithTimeout = (url, timeout = 10000) => {
        return Promise.race([
            fetch(url).then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            }),
            new Promise((_, reject) =>
                setTimeout(() => reject(new Error('Request timeout')), timeout)
            )
        ]);
    };

    Promise.all([
        fetchWithTimeout('https://vanygroup.id/api/vny/data', 15000)
            .catch(error => {
                console.error('VNY Data API Error:', error.message);
                return null;
            }),
        fetchWithTimeout('https://vanygroup.id/api/vny/homepage/site-config', 15000)
            .catch(error => {
                console.error('Site Config API Error:', error.message);
                return null;
            })
    ]).then(([dataResponse, configResponse]) => {
        // Hide loading indicator
        document.getElementById('loadingIndicator').style.display = 'none';

        let apiSuccess = false;
        console.log('Data Response:', dataResponse);
        if (dataResponse && (dataResponse.categories || dataResponse.featuredProducts || dataResponse.heroSections)) {
            vnyData = dataResponse;
            apiSuccess = true;
        } else {
            showApiError();
            vnyData = getFallbackData();
        }

        if (configResponse && configResponse.success) {
            siteConfig = configResponse.data || {};
        } else {
            siteConfig = getFallbackSiteConfig();
        }

        // Show success notification if API worked
        console.log('VNY Data:', vnyData);
        if (apiSuccess) {
            showApiSuccess();
            console.log('✅ VNY API berhasil dimuat!');
            console.log('📊 Data struktur:', {
                categories: vnyData.categories ? vnyData.categories.length : 0,
                featuredProducts: vnyData.featuredProducts ? vnyData.featuredProducts.length : 0,
                heroSections: vnyData.heroSections ? vnyData.heroSections.length : 0,
                productGridItems: vnyData.productGrid && vnyData.productGrid.items ? vnyData.productGrid.items.length : 0
            });
        } else {
            console.log('❌ API gagal dimuat, menggunakan fallback data');
            console.log('Data response:', dataResponse);
            console.log('Config response:', configResponse);
        }

        // Initialize all sections with proper delay for jQuery/Owl Carousel
        setTimeout(() => {
            console.log('🚀 Starting initialization...');
            initHeroSlider();
            initCategories();
            initNewsProducts();
            initOurCollection();
            initSpecialOffer();
            console.log('✅ All sections initialized');
        }, 500);
    }).catch(error => {
        // Hide loading indicator
        document.getElementById('loadingIndicator').style.display = 'none';

        console.error('API Loading Error:', error);
        showApiError();
        vnyData = getFallbackData();
        siteConfig = getFallbackSiteConfig();
        initHeroSlider();
        initCategories();
        initNewsProducts();
        initOurCollection();
        initSpecialOffer();
    });

    function showApiError() {
        document.getElementById('apiErrorNotification').style.display = 'block';
        setTimeout(() => {
            document.getElementById('apiErrorNotification').style.display = 'none';
        }, 5000);
    }

    function showApiSuccess() {
        document.getElementById('apiSuccessNotification').style.display = 'block';
        setTimeout(() => {
            document.getElementById('apiSuccessNotification').style.display = 'none';
        }, 3000);
    }

    function getFallbackData() {
        return {
            categories: [
                {
                    id: 1,
                    name: "Low Top Motif Gorga",
                    description: "Sepatu casual etnic dengan motif gorga khas yang unik",
                    image: "/temp/nike-just-do-it(6).jpg"
                },
                {
                    id: 2,
                    name: "Sneakers Motif Ulu Paung",
                    description: "Sepatu etnic dengan motif paung khas yang unik",
                    image: "/temp/nike-just-do-it(7).jpg"
                },
                {
                    id: 3,
                    name: "Tumtuman",
                    description: "Sarung selendang tumtuman",
                    image: "/temp/nike-just-do-it(8).jpg"
                }
            ],
            featuredProducts: [
                {
                    id: 1,
                    name: "Gorga Navy",
                    description: "Sepatu Dengan Desain Batak Motif Gorga - Navy",
                    price: 400000,
                    image: "/temp/nike-just-do-it(6).jpg",
                    category: { name: "Low Top Motif Gorga" }
                },
                {
                    id: 2,
                    name: "Ulu Paung Maroon",
                    description: "Sepatu Dengan Desain Batak Motif Ulu Paung - Maroon",
                    price: 450000,
                    image: "/temp/nike-just-do-it(7).jpg",
                    category: { name: "Sneakers Motif Ulu Paung" }
                }
            ],
            heroSections: [
                {
                    id: 1,
                    title: "Ulu Paung Maroon",
                    subtitle: "Premium Collection",
                    description: "Premium Collection Motif Ulu Paung Maroon",
                    price: "Rp. 450.000",
                    image: "/temp/nike-just-do-it(6).jpg"
                }
            ],
            productGrid: {
                items: [
                    {
                        id: 1,
                        title: "Edisi Terbatas",
                        subtitle: "Sepatu Casual Nyaman & Trendy",
                        image: "/temp/nike-just-do-it(6).jpg"
                    }
                ]
            }
        };
    }

    function getFallbackSiteConfig() {
        return {
            siteName: "VNY Store",
            heroTitle: "Welcome to VNY Store",
            heroDescription: "Discover premium sneakers and streetwear collections. Quality meets style in every piece we curate for you.",
            sectionTitle: "Featured Products"
        };
    }

    function updatePageContent() {
        // Update hero section with site config
        const heroTitle = document.querySelector('.vny-hero h1');
        const heroDesc = document.querySelector('.vny-hero p');
        const sectionTitle = document.querySelector('.vny-section-title');

        if (heroTitle && siteConfig.heroTitle) {
            heroTitle.textContent = siteConfig.heroTitle;
        }

        if (heroDesc && siteConfig.heroDescription) {
            heroDesc.textContent = siteConfig.heroDescription;
        }

        if (sectionTitle && siteConfig.sectionTitle) {
            sectionTitle.textContent = siteConfig.sectionTitle;
        }

        // Update page title
        if (siteConfig.siteName) {
            document.title = siteConfig.siteName + ' - Premium Collection';
        }
    }

    function renderProducts() {
        const productGrid = document.getElementById('productGrid');

        if (vnyData && vnyData.length > 0) {
            productGrid.innerHTML = vnyData.map(product => `
                <div class="vny-product-card" onclick="showProductDetail(${product.id})">
                    <div class="vny-product-image">
                        <img src="${product.image || '/temp/nike-just-do-it(6).jpg'}"
                             alt="${product.name || product.title}"
                             style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;"
                             onerror="this.parentElement.innerHTML='<span>Image not available</span>'">
                    </div>
                    <h3 class="vny-product-title">${product.name || product.title}</h3>
                    <p class="vny-product-desc">${product.description || 'Premium quality product'}</p>
                    <div class="vny-product-price">${product.price || 'Price on request'}</div>
                    ${product.category ? `<div class="vny-product-category">${product.category}</div>` : ''}
                </div>
            `).join('');
        } else {
            productGrid.innerHTML = `
                <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #6c757d;">
                    <div style="font-size: 48px; margin-bottom: 20px;">📦</div>
                    <h3 style="font-size: 24px; margin-bottom: 10px; color: #495057;">No Products Available</h3>
                    <p>Check back later for new arrivals</p>
                </div>
            `;
        }
    }

    function showProductDetail(productId) {
        console.log('Opening product detail for ID:', productId);

        // Find product from different sources
        let product = null;

        // Search in featuredProducts
        if (vnyData && vnyData.featuredProducts) {
            product = vnyData.featuredProducts.find(p => p.id === productId);
        }

        // Search in categories if not found
        if (!product && vnyData && vnyData.categories) {
            product = vnyData.categories.find(p => p.id === productId);
        }

        // Search in productGrid items if not found
        if (!product && vnyData && vnyData.productGrid && vnyData.productGrid.items) {
            product = vnyData.productGrid.items.find(p => p.id === productId);
        }

        // Use fallback product if not found
        if (!product) {
            product = {
                id: productId || 1,
                title: "Sepatu Etnik Premium VNY",
                name: "Sepatu Etnik Premium VNY",
                description: "Sepatu dengan desain etnik modern yang memadukan tradisi dan gaya kontemporer",
                price: "Rp. 450.000",
                image: "https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600&h=600&fit=crop&crop=center",
                tag: "Premium Collection",
                category: { name: "Sepatu Etnik" }
            };
        }

        console.log('Product found:', product);
        openProductModal(product);
    }

    function showCategoryDetail(categoryId) {
        console.log('Opening category detail for ID:', categoryId);

        // Find category from API data
        let category = null;

        if (vnyData && vnyData.categories) {
            category = vnyData.categories.find(c => c.id === categoryId);
        }

        // Use fallback category data based on ID
        if (!category) {
            const categoryData = {
                100: {
                    title: "Sepatu Etnik Motif Gorga",
                    description: "Sepatu dengan motif Gorga khas Batak yang unik dan stylish. Gorga adalah seni ukir tradisional Batak yang memiliki makna filosofis mendalam, melambangkan kehidupan, keindahan, dan perlindungan.",
                    image: "https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600&h=600&fit=crop&crop=center",
                    tag: "Kategori Unggulan",
                    category: "Sepatu Etnik"
                },
                101: {
                    title: "Koleksi Ulu Paung Maroon",
                    description: "Sepatu etnik motif Ulu Paung dalam warna maroon yang elegan. Ulu Paung adalah motif tradisional Batak yang melambangkan kehormatan dan martabat, sempurna untuk gaya modern etnik.",
                    image: "https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=600&h=600&fit=crop&crop=center",
                    tag: "Kategori Premium",
                    category: "Sepatu Etnik"
                },
                102: {
                    title: "Kain Tradisional Batak",
                    description: "Koleksi kain tradisional Batak dengan motif autentik yang diwariskan turun-temurun. Setiap motif memiliki cerita dan makna yang mendalam dalam budaya Batak, cocok untuk acara formal dan kasual.",
                    image: "https://images.unsplash.com/photo-1551107696-a4b0c5a0d9a2?w=600&h=600&fit=crop&crop=center",
                    tag: "Warisan Budaya",
                    category: "Kain Tradisional"
                }
            };

            category = categoryData[categoryId] || categoryData[100];
        }

        // Convert category to product format for modal
        const categoryAsProduct = {
            id: categoryId,
            title: category.title || category.name,
            name: category.title || category.name,
            description: category.description,
            price: "Mulai dari Rp. 300.000",
            image: category.image,
            tag: category.tag || "Kategori",
            category: category.category || "VNY Collection"
        };

        console.log('Category found:', categoryAsProduct);
        openProductModal(categoryAsProduct);
    }

    function showCollectionDetail(collectionId, imageUrl, title, subtitle) {
        console.log('Opening collection detail for ID:', collectionId);

        // Find collection from API data
        let collection = null;

        if (vnyData && vnyData.productGrid && vnyData.productGrid.items) {
            collection = vnyData.productGrid.items.find(c => c.id === collectionId);
        }

        // Use passed parameters or fallback collection data based on ID
        if (!collection) {
            const collectionData = {
                200: {
                    title: title || "Brand Motion",
                    subtitle: subtitle || "Identity in motion",
                    description: "Translate marks into kinetic systems. Timing, easing, and restraint create memory. Desain yang menggabungkan gerakan dinamis dengan identitas visual yang kuat.",
                    image: imageUrl || "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&h=1080&fit=crop&crop=center"
                },
                201: {
                    title: title || "Timeless Style",
                    subtitle: subtitle || "Beyond trends",
                    description: "Creating lasting impressions through thoughtful design. Every element serves a purpose, every motion tells a story of craftsmanship and innovation.",
                    image: imageUrl || "https://images.unsplash.com/photo-1519904981063-b0cf448d479e?w=1920&h=1080&fit=crop&crop=center"
                },
                202: {
                    title: title || "Crafted Excellence",
                    subtitle: subtitle || "Artisan quality",
                    description: "Where tradition meets innovation. Each piece represents hours of dedication and the finest materials, creating products that stand the test of time.",
                    image: imageUrl || "https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1920&h=1080&fit=crop&crop=center"
                }
            };

            collection = collectionData[collectionId] || collectionData[200];
        }

        // Override with passed parameters if available
        if (imageUrl) collection.image = imageUrl;
        if (title) collection.title = title;
        if (subtitle) collection.subtitle = subtitle;

        console.log('Collection found:', collection);
        openCollectionModal(collection);
    }

    function openCollectionModal(collection) {
        const modal = document.getElementById('collectionModal');
        const bgImageElement = document.getElementById('collectionModalBg');

        // Populate modal with collection data
        document.getElementById('collectionModalTitle').textContent = collection.title || collection.name || 'Brand Motion';
        document.getElementById('collectionModalSubtitle').textContent = collection.subtitle || 'Identity in motion';
        document.getElementById('collectionModalDescription').textContent = collection.description || 'Translate marks into kinetic systems. Timing, easing, and restraint create memory.';

        // Set background image with debugging
        const bgImage = collection.image || 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&h=1080&fit=crop&crop=center';
        console.log('Setting background image:', bgImage);

        if (bgImageElement) {
            bgImageElement.src = bgImage;
            bgImageElement.onload = function() {
                console.log('Background image loaded successfully');
            };
            bgImageElement.onerror = function() {
                console.log('Background image failed to load, using fallback');
                bgImageElement.src = 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&h=1080&fit=crop&crop=center';
            };
        } else {
            console.error('Background image element not found');
        }

        // Show modal with fade in animation
        modal.classList.remove('hidden');
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.style.transition = 'opacity 0.5s ease-in-out';
            modal.style.opacity = '1';
        }, 10);

        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }

    function closeCollectionModal() {
        console.log('closeCollectionModal called');
        const modal = document.getElementById('collectionModal');

        if (!modal) {
            console.error('Collection modal element not found');
            return;
        }

        console.log('Closing collection modal');

        // Hide modal with fade out animation
        modal.style.transition = 'opacity 0.3s ease-in-out';
        modal.style.opacity = '0';

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.style.opacity = '1'; // Reset opacity for next time
            // Restore body scroll
            document.body.style.overflow = 'auto';
            console.log('Collection modal closed successfully');
        }, 300);
    }

    function initHeroSlider() {
        console.log('=== initHeroSlider started ===');
        console.log('vnyData:', vnyData);
        console.log('jQuery available:', typeof $ !== 'undefined');

        // Use heroSections from API data
        let heroSlides = [];

        if (vnyData && vnyData.heroSections && vnyData.heroSections.length > 0) {
            console.log('Using API heroSections:', vnyData.heroSections.length);
            heroSlides = vnyData.heroSections.map(hero => ({
                title: hero.title || "Premium Product",
                subtitle: hero.subtitle || "Premium Collection",
                description: hero.description || "Produk berkualitas tinggi dengan desain yang menarik",
                price: hero.price || "Harga tersedia",
                image: hero.image || "/temp/nike-just-do-it(6).jpg"
            }));
        }

        // Fallback slides if no API data
        if (heroSlides.length === 0) {
            console.log('Using fallback hero slides');
            heroSlides = [
                {
                    title: "VNY Store",
                    subtitle: "Premium Collection",
                    description: "Koleksi premium dengan kualitas terbaik dan desain menarik",
                    price: "Rp. 300.000",
                    image: "https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400&h=400&fit=crop&crop=center"
                },
                {
                    title: "Koleksi Etnik Modern",
                    subtitle: "Tradisi Bertemu Modernitas",
                    description: "Perpaduan sempurna antara nilai tradisional dan desain kontemporer",
                    price: "Rp. 450.000",
                    image: "https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=400&h=400&fit=crop&crop=center"
                },
                {
                    title: "Sepatu Premium",
                    subtitle: "Kualitas Terbaik",
                    description: "Diproduksi dengan bahan berkualitas tinggi dan craftsmanship terbaik",
                    price: "Rp. 500.000",
                    image: "https://images.unsplash.com/photo-1551107696-a4b0c5a0d9a2?w=400&h=400&fit=crop&crop=center"
                },
                {
                    title: "Limited Edition",
                    subtitle: "Edisi Terbatas",
                    description: "Koleksi eksklusif dengan desain unik dan terbatas",
                    price: "Rp. 650.000",
                    image: "https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=400&h=400&fit=crop&crop=center"
                }
            ];
        }

        // Wait for jQuery to load before initializing Owl Carousel
        if (typeof $ === 'undefined') {
            console.log('Waiting for jQuery to load...');
            setTimeout(() => {
                initHeroSlider();
            }, 500);
            return;
        }

        const owlCarousel = $('#heroOwlCarousel');

        // Check if carousel element exists
        if (!owlCarousel.length) {
            console.error('Owl Carousel element not found!');
            return;
        }

        // Destroy existing carousel if it exists
        if (owlCarousel.hasClass('owl-loaded')) {
            owlCarousel.trigger('destroy.owl.carousel');
        }

        // Clear existing slides
        owlCarousel.html('');
        console.log('Cleared carousel, adding', heroSlides.length, 'slides');

        // Create slides
        heroSlides.forEach((slide, index) => {
            console.log('Adding slide', index + 1, ':', slide.title);
            const slideHtml = `
                <div class="hero-slide" style="display: flex !important; align-items: center !important; justify-content: space-between !important; padding: 40px 5% !important; height: 100% !important; background: linear-gradient(135deg, #8B0000 0%, #DC143C 100%) !important;">
                    <div class="hero-content" style="flex: 1 !important; color: white !important; padding: 20px !important;">
                        <h1 style="font-size: 3rem !important; font-weight: 800 !important; margin-bottom: 1rem !important; color: white !important; text-transform: uppercase !important;">${slide.title}</h1>
                        <div class="subtitle" style="font-size: 1.1rem !important; margin-bottom: 0.5rem !important; color: white !important; opacity: 0.9 !important; text-transform: uppercase !important;">${slide.subtitle}</div>
                        <p style="color: white !important; opacity: 0.8 !important; margin-bottom: 1.5rem !important;">${slide.description || 'Koleksi premium dengan kualitas terbaik'}</p>
                        <div class="hero-price" style="font-size: 2.5rem !important; font-weight: 700 !important; margin: 1.5rem 0 !important; color: white !important;">${slide.price}</div>
                        <a href="#" class="hero-btn" style="display: inline-block !important; padding: 12px 24px !important; background: white !important; color: #333 !important; font-weight: 600 !important; text-decoration: none !important; border-radius: 25px !important;">Shop Now</a>
                    </div>
                    <div class="hero-image" style="flex: 1 !important; display: flex !important; justify-content: center !important; align-items: center !important; padding: 20px !important;">
                        <img src="${slide.image}" alt="${slide.title}" style="max-width: 350px !important; max-height: 350px !important; object-fit: contain !important; filter: drop-shadow(0 15px 40px rgba(0,0,0,0.4)) !important;" onerror="this.src='https://images.unsplash.com/photo-1549298916-b41d501d3772?w=350&h=350&fit=crop&crop=center'">
                    </div>
                </div>
            `;
            owlCarousel.append(slideHtml);
        });

        console.log('All slides added. Carousel HTML:', owlCarousel.html().length, 'characters');
        console.log('Number of slide elements:', owlCarousel.children().length);
        console.log('Initializing Owl Carousel...');

        // Initialize Owl Carousel
        try {
            owlCarousel.owlCarousel({
                items: 1,
                loop: heroSlides.length > 1,
                autoplay: heroSlides.length > 1,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                nav: true,
                dots: true,
                navText: ['‹', '›'],
                smartSpeed: 800,
                margin: 0,
                responsive: {
                    0: {
                        nav: false,
                        dots: true
                    },
                    768: {
                        nav: true,
                        dots: true
                    }
                },
                onInitialized: function(event) {
                    console.log('✅ Owl Carousel initialized successfully!');
                    console.log('Total items:', event.item.count);
                    console.log('Current item index:', event.item.index);

                    // Force refresh layout
                    setTimeout(() => {
                        owlCarousel.trigger('refresh.owl.carousel');
                        console.log('🔄 Carousel refreshed');
                    }, 100);
                },
                onInitialize: function(event) {
                    console.log('🔄 Owl Carousel initializing...');
                }
            });
            console.log('Owl Carousel initialization command completed');
        } catch (error) {
            console.error('❌ Error initializing Owl Carousel:', error);
        }

        console.log('=== initHeroSlider completed ===');
    }

    function updateSlider() {
        const sliderTrack = document.getElementById('heroSliderTrack');
        const dots = document.querySelectorAll('.slider-dot');
        const counter = document.getElementById('sliderCounter');

        if (sliderTrack) {
            // Calculate the exact translation based on current slide
            const translateX = -currentSlide * (100 / totalSlides);
            sliderTrack.style.transform = `translateX(${translateX}%)`;
        }

        // Update dots
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentSlide);
        });

        // Update counter
        if (counter) {
            counter.textContent = `${String(currentSlide + 1).padStart(2, '0')} / ${String(totalSlides).padStart(2, '0')}`;
        }
    }

    function initCategories() {
        let categories = [];

        if (vnyData && vnyData.categories && vnyData.categories.length > 0) {
            categories = vnyData.categories.slice(0, 3).map(category => ({
                title: category.name || "Kategori Premium",
                description: category.description || `Koleksi ${category.name} berkualitas tinggi`,
                image: category.image || "/temp/nike-just-do-it(6).jpg",
                id: category.id
            }));
        }

        // Fallback categories if no API data or categories
        if (categories.length === 0) {
            categories = [
                {
                    title: "Sepatu Etnik Motif Gorga",
                    description: "Sepatu dengan motif Gorga khas Batak yang unik dan stylish, tersedia dalam berbagai ukuran lengkap",
                    image: "https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400&h=300&fit=crop&crop=center"
                },
                {
                    title: "Koleksi Ulu Paung Maroon",
                    description: "Sepatu etnik motif Ulu Paung dalam warna maroon yang elegan, sempurna untuk gaya modern etnik",
                    image: "https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=400&h=300&fit=crop&crop=center"
                },
                {
                    title: "Kain Tradisional Batak",
                    description: "Koleksi kain tradisional Batak dengan motif autentik, cocok untuk acara formal dan kasual",
                    image: "https://images.unsplash.com/photo-1551107696-a4b0c5a0d9a2?w=400&h=300&fit=crop&crop=center"
                }
            ];
        }

        const categoriesGrid = document.getElementById('categoriesGrid');
        categoriesGrid.innerHTML = categories.map((category, index) => `
            <div class="relative overflow-hidden transition-all duration-300 bg-white rounded-lg cursor-pointer group hover:shadow-xl" onclick="showCategoryDetail(${category.id || index + 100})">
                <div class="aspect-[4/3] overflow-hidden">
                    <img src="${category.image}" alt="${category.title}"
                         class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-105"
                         onerror="this.src='https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400&h=300&fit=crop&crop=center'">
                </div>
                <div class="p-6">
                    <h3 class="mb-3 text-lg font-bold text-gray-900 line-clamp-2">${category.title}</h3>
                    <p class="mb-4 text-sm text-gray-600 line-clamp-3">${category.description}</p>
                    <button class="w-full text-center vny-btn-secondary" onclick="event.stopPropagation();">Explore Collection →</button>
                </div>
            </div>
        `).join('');
    }

    function initNewsProducts() {
        let newsProducts = [];

        if (vnyData && vnyData.productGrid && vnyData.productGrid.items && vnyData.productGrid.items.length > 0) {
            // Use productGrid items for news section
            newsProducts = vnyData.productGrid.items.slice(0, 3).map(item => ({
                title: item.subtitle || item.title || "Produk Berkualitas",
                description: `${item.title} - Koleksi terbaru dengan kualitas terbaik`,
                image: item.image || "/temp/nike-just-do-it(7).jpg",
                tag: item.title || "Produk Terbaru",
                id: item.id
            }));
        }

        // Fallback news products if no API data
        if (newsProducts.length === 0) {
            newsProducts = [
                {
                    title: "Sepatu Etnik Gorga Premium",
                    description: "Koleksi terbaru dengan motif Gorga khas Batak yang autentik dan nyaman",
                    image: "https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400&h=300&fit=crop&crop=center",
                    tag: "New Arrival",
                    price: "Rp. 450.000"
                },
                {
                    title: "Ulos Tradisional Modern",
                    description: "Perpaduan tradisi dan modernitas dalam kain Ulos berkualitas tinggi",
                    image: "https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=400&h=300&fit=crop&crop=center",
                    tag: "Limited Edition",
                    price: "Rp. 350.000"
                },
                {
                    title: "Tas Etnik Minimalis",
                    description: "Tas dengan sentuhan etnik modern yang cocok untuk segala aktivitas",
                    image: "https://images.unsplash.com/photo-1551107696-a4b0c5a0d9a2?w=400&h=300&fit=crop&crop=center",
                    tag: "Best Seller",
                    price: "Rp. 250.000"
                }
            ];
        }

        const newsGrid = document.getElementById('newsGrid');
        // Clear loading state first
        newsGrid.innerHTML = '';

        // Generate products with screenshot-style layout
        newsGrid.innerHTML = newsProducts.map(product => `
            <div class="relative overflow-hidden transition-all duration-300 bg-white cursor-pointer rounded-xl group hover:shadow-xl" onclick="${product.id ? `showProductDetail(${product.id})` : '#'}">
                <!-- Product Image -->
                <div class="relative overflow-hidden">
                    <img src="${product.image}" alt="${product.title}"
                         class="object-cover w-full h-64 transition-transform duration-300 group-hover:scale-105"
                         onerror="this.src='https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400&h=320&fit=crop&crop=center'">
                    <!-- Tag di kiri atas -->
                    <div class="absolute top-0 left-0 px-3 py-1 text-xs font-bold text-white bg-red-500 rounded-br-lg">${product.tag}</div>
                </div>

                <!-- Content Area -->
                <div class="p-6 space-y-4">
                    <!-- Product Title -->
                    <h3 class="text-xl font-bold text-gray-900 line-clamp-2">${product.title}</h3>

                    <!-- Product Description dengan icon -->
                    <div class="flex items-start space-x-2">
                        <div class="flex-shrink-0 mt-1">
                            <span class="text-yellow-500">⭐</span>
                        </div>
                        <p class="text-sm text-gray-600 line-clamp-2">${product.description}</p>
                    </div>

                    <!-- Price if available -->
                    ${product.price ? `<div class="text-lg font-bold text-vny-dark-red">${product.price}</div>` : ''}

                    <!-- Button -->
                    <button class="w-full px-4 py-3 mt-4 text-sm font-medium text-gray-800 transition-colors duration-200 bg-gray-100 rounded-lg hover:bg-gray-200" onclick="event.stopPropagation();">
                        Lihat Sekarang →
                    </button>
                </div>
            </div>
        `).join('');
    }

    function initOurCollection() {
        let collections = [];

        if (vnyData && vnyData.productGrid && vnyData.productGrid.items && vnyData.productGrid.items.length > 0) {
            // Use productGrid items that match collection patterns
            const collectionItems = vnyData.productGrid.items.filter(item =>
                item.title && (
                    item.title.includes('SALE') ||
                    item.title.includes('Varian') ||
                    item.title.includes('Best Seller') ||
                    item.title.includes('Universal') ||
                    item.title.includes('Unggulan')
                )
            );

            collections = collectionItems.slice(0, 3).map(item => ({
                title: item.title || "Koleksi Spesial",
                subtitle: item.subtitle || "Koleksi Premium",
                image: item.image || "/temp/nike-just-do-it(6).jpg",
                id: item.id
            }));
        }

        // Fallback collections if no API data
        if (collections.length === 0) {
            collections = [
                {
                    title: "SALE Akhir Tahun",
                    subtitle: "Varian untuk Penawaran Diskon",
                    image: "/temp/nike-just-do-it(6).jpg"
                },
                {
                    title: "Gaya Tanpa Batas Usia",
                    subtitle: "Varian Universal",
                    image: "/temp/nike-just-do-it(7).jpg"
                },
                {
                    title: "Best Seller Pilihan",
                    subtitle: "Varian untuk Produk Unggulan",
                    image: "/temp/nike-just-do-it(8).jpg"
                }
            ];
        }

        const collectionGrid = document.getElementById('collectionGrid');
        collectionGrid.innerHTML = collections.map((collection, index) => `
            <div class="relative overflow-hidden transition-all duration-300 rounded-lg cursor-pointer group hover:shadow-xl" onclick="showCollectionDetail(${collection.id || index + 200}, '${collection.image}', '${collection.title}', '${collection.subtitle}')">
                <img src="${collection.image}" alt="${collection.title}"
                     class="object-cover w-full h-64 transition-transform duration-300 group-hover:scale-105"
                     onerror="this.src='/temp/nike-just-do-it(6).jpg'">
                <div class="absolute inset-0 flex flex-col justify-end p-6 transition-opacity duration-300 bg-black bg-opacity-40 group-hover:bg-opacity-50">
                    <div class="mb-2 text-xl font-bold text-white">${collection.title}</div>
                    <div class="text-sm text-white opacity-90">${collection.subtitle}</div>
                    ${collection.productCount ? `<div class="mt-2 text-sm text-white opacity-80">${collection.productCount} Produk</div>` : ''}
                </div>
            </div>
        `).join('');
    }

    function initSpecialOffer() {
        const specialOfferContent = document.getElementById('specialOfferContent');

        let offerTitle = "Penawaran Spesial!";
        let offerSubtitle = "Dapatkan diskon hingga 50% untuk koleksi pilihan";
        let apiStats = "";

        // Use API data to create dynamic offer content
        if (vnyData && (vnyData.featuredProducts || vnyData.categories || vnyData.heroSections)) {
            const categoryCount = vnyData.categories ? vnyData.categories.length : 0;
            const productCount = vnyData.featuredProducts ? vnyData.featuredProducts.length : 0;
            const heroCount = vnyData.heroSections ? vnyData.heroSections.length : 0;

            if (productCount > 0) {
                offerTitle = `${productCount} Produk Unggulan Tersedia!`;
                offerSubtitle = categoryCount > 0 ?
                    `Jelajahi ${categoryCount} kategori dengan ${productCount} produk berkualitas tinggi` :
                    `${productCount} produk premium siap untuk Anda`;
            } else {
                offerTitle = "Koleksi VNY Store!";
                offerSubtitle = "Temukan produk etnik berkualitas dengan motif khas Batak";
            }

            apiStats = `
                <div class="api-status" style="margin-top: 30px; font-size: 0.9rem; opacity: 0.8;">
                    ✅ Live from VNY API | ${categoryCount} kategori • ${productCount} produk • ${heroCount} hero slides
                </div>
            `;
        }

        specialOfferContent.innerHTML = `
            <div class="mb-4 text-4xl font-bold text-center text-white animate-fade-in">${offerTitle}</div>
            <div class="max-w-2xl mx-auto mb-8 text-xl text-center text-white opacity-90 animate-slide-up">${offerSubtitle}</div>
            <div class="text-center animate-slide-up">
                <a href="#" class="inline-block vny-btn">Belanja Sekarang</a>
            </div>
            ${apiStats}
        `;
    }

    // Modal functions
    function openProductModal(product) {
        const modal = document.getElementById('productModal');
        const modalContent = document.getElementById('modalContent');

        // Populate modal with simple product data (only image, title, description)
        document.getElementById('modalTitle').textContent = product.title || product.name || 'Detail Produk';
        document.getElementById('modalProductTitle').textContent = product.title || product.name || 'Premium Product';
        document.getElementById('modalDescription').textContent = product.description || 'Produk berkualitas tinggi dengan desain motif etnik khas Batak yang memadukan tradisi dengan gaya modern';

        // Set main image only
        const mainImage = product.image || 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600&h=600&fit=crop&crop=center';
        document.getElementById('modalImage').src = mainImage;

        // Show modal with animation
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);

        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }

    function closeProductModal() {
        const modal = document.getElementById('productModal');
        const modalContent = document.getElementById('modalContent');

        // Hide modal with animation
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
            // Restore body scroll
            document.body.style.overflow = 'auto';
        }, 300);
    }

    function showTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });

        // Remove active state from all buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('border-vny-red', 'text-vny-red', 'active');
            button.classList.add('border-transparent', 'text-gray-500');
        });

        // Show selected tab
        document.getElementById(tabName + 'Tab').classList.remove('hidden');

        // Set active button
        const activeButton = document.querySelector(`[data-tab=\"${tabName}\"]`);
        activeButton.classList.remove('border-transparent', 'text-gray-500');
        activeButton.classList.add('border-vny-red', 'text-vny-red', 'active');
    }

    // Gallery image interaction
    document.addEventListener('click', function(e) {
        if (e.target.matches('#galleryTab img')) {
            // Show larger image or implement lightbox
            const imgSrc = e.target.src;
            const mainImage = document.getElementById('modalImage');
            if (mainImage && imgSrc) {
                mainImage.src = imgSrc;
            }
        }
    });

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('productModal');
        if (e.target === modal) {
            closeProductModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeProductModal();
        }
    });

    // Make functions globally available
    window.goToSlide = (index) => {
        const owlCarousel = $('#heroOwlCarousel');
        if (owlCarousel.length && typeof owlCarousel.trigger === 'function') {
            owlCarousel.trigger('to.owl.carousel', [index, 300]);
        }
    };

    window.scrollToSection = (sectionClass) => {
        const section = document.querySelector(`.${sectionClass}`);
        if (section) {
            section.scrollIntoView({ behavior: 'smooth' });
        }
    };

    window.showProductDetail = showProductDetail;
    window.openProductModal = openProductModal;
    window.closeProductModal = closeProductModal;
    window.showProductDetail = showProductDetail;
    window.showCategoryDetail = showCategoryDetail;
    window.showCollectionDetail = showCollectionDetail;
    window.openCollectionModal = openCollectionModal;
    window.closeCollectionModal = closeCollectionModal;

    // Add event listeners sebagai backup
    const closeBtn1 = document.getElementById('collectionModalCloseBtn');
    const closeBtn2 = document.getElementById('collectionModalCloseBtn2');
    const modal = document.getElementById('collectionModal');

    if (closeBtn1) {
        closeBtn1.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Close button 1 clicked via event listener');
            closeCollectionModal();
        });
    }

    if (closeBtn2) {
        closeBtn2.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Close button 2 clicked via event listener');
            closeCollectionModal();
        });
    }

    // Close modal ketika klik di luar modal
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                console.log('Modal background clicked');
                closeCollectionModal();
            }
        });
    }

    // Close modal dengan ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            console.log('ESC key pressed');
            closeCollectionModal();
        }
    });
});

// Global fallback functions (jika DOMContentLoaded belum selesai)
function closeCollectionModal() {
    console.log('Global closeCollectionModal called');
    const modal = document.getElementById('collectionModal');

    if (!modal) {
        console.error('Collection modal element not found');
        return;
    }

    console.log('Closing collection modal (global)');

    // Hide modal immediately
    modal.classList.add('hidden');
    // Restore body scroll
    document.body.style.overflow = 'auto';
    console.log('Collection modal closed successfully (global)');
}

function openCollectionModal(collection) {
    console.log('Global openCollectionModal called');
    // Call the internal function if available
    if (window.openCollectionModal && typeof window.openCollectionModal === 'function') {
        window.openCollectionModal(collection);
    }
}
</script>
@endsection
