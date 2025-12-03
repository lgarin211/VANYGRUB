@extends('layouts.app')

@section('title', 'VNY - Premium Sneakers Collection')

@section('styles')
@import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: "Poppins", sans-serif;
  background: #fafafa url('https://asset.kompas.com/crops/eV3pVhsTUUlB_nffNUO84gOd4UQ=/34x11:951x622/750x500/data/photo/2022/02/28/621c6c100fc46.jpg') repeat;
  background-size: 300px 200px;
  opacity: 0.9;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  user-select: none;
  padding: 40px 20px;
  position: relative;
}

body::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(250, 250, 250, 0.7);
  z-index: 0;
  pointer-events: none;
}

.header {
  text-align: center;
  margin-bottom: 60px;
  position: relative;
  z-index: 1;
}

.subtitle {
  color: #ff6b35;
  font-size: 14px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 2px;
  margin-bottom: 16px;
}

.main-title {
  font-size: clamp(36px, 5vw, 56px);
  font-weight: 900;
  color: #0a0a0a;
  line-height: 1.1;
}

.slider-container {
  perspective: 1500px;
  perspective-origin: 50% 50%;
  cursor: grab;
  width: 100%;
  max-width: 1400px;
  overflow: hidden;
  position: relative;
  z-index: 1;
}

.slider-container.dragging {
  cursor: grabbing;
}

.slider-track {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transform-style: preserve-3d;
}

.card {
  flex-shrink: 0;
  width: 240px;
  background: white;
  overflow: hidden;
  transform-style: preserve-3d;
  position: relative;
  cursor: pointer;
}

.card::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(
    to right,
    rgba(0, 0, 0, 0.15),
    transparent 30%,
    transparent 70%,
    rgba(0, 0, 0, 0.15)
  );
  transform: translateZ(-8px);
  pointer-events: none;
}

.card::after {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: #e0e0e0;
  transform: translateZ(-16px);
  box-shadow: 0 0 40px rgba(0, 0, 0, 0.3);
  pointer-events: none;
}

.card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  pointer-events: none;
  position: relative;
  z-index: 1;
}

.card .hover-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
  z-index: 2;
}

.card:hover .hover-overlay {
  opacity: 1;
}

.card .hover-overlay span {
  color: white;
  font-size: 16px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.slider-track.blurred .card:not(.expanded) {
  filter: blur(8px);
  transition: filter 0.6s ease;
}

.card.expanded {
  z-index: 1000 !important;
}

.card-info {
  position: fixed;
  bottom: 80px;
  left: 50%;
  transform: translateX(-50%);
  text-align: center;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.6s ease;
  z-index: 1001;
  max-width: 600px;
  padding: 1.5rem;
  background: #ff6b35;
  box-shadow: 4px 3px 18px 4px #b7b7b721;
  min-width: 375px;
}

.card-info.visible {
  opacity: 1;
  pointer-events: all;
}

.card-info h2 {
  font-size: 36px;
  font-weight: 900;
  color: #0a0a0a;
  margin-bottom: 16px;
}

.card-info p {
  font-size: 18px;
  color: #080808;
  line-height: 1.7;
}

.close-btn {
  position: fixed;
  top: 40px;
  right: 40px;
  width: 60px;
  height: 60px;
  background: white;
  border: none;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  z-index: 1002;
  opacity: 0;
  pointer-events: none;
  transition: all 0.3s ease;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.close-btn.visible {
  opacity: 1;
  pointer-events: all;
}

.close-btn:hover {
  background: #ff6b35;
  color: white;
  transform: rotate(90deg) scale(1.1);
}

.close-btn svg {
  width: 24px;
  height: 24px;
}

/* Modal Popup Styles for Gallery2 */
.gallery2-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.95);
  backdrop-filter: blur(15px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2000;
  animation: gallery2ModalFadeIn 0.3s ease-out;
}

@keyframes gallery2ModalFadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.gallery2-modal-content {
  position: relative;
  max-width: 90vw;
  max-height: 90vh;
  display: flex;
  background: linear-gradient(135deg, rgba(128, 0, 0, 0.15), rgba(0, 0, 0, 0.1));
  backdrop-filter: blur(25px);
  border-radius: 25px;
  border: 1px solid rgba(255, 255, 255, 0.15);
  overflow: hidden;
  animation: gallery2ModalSlideIn 0.4s ease-out;
  box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
}

@keyframes gallery2ModalSlideIn {
  from {
    transform: translateY(60px) scale(0.9);
    opacity: 0;
  }
  to {
    transform: translateY(0) scale(1);
    opacity: 1;
  }
}

.gallery2-modal-close {
  position: absolute;
  top: 25px;
  right: 25px;
  z-index: 2001;
  background: rgba(128, 0, 0, 0.2);
  backdrop-filter: blur(15px);
  border: none;
  border-radius: 50%;
  width: 55px;
  height: 55px;
  color: white;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.gallery2-modal-close:hover {
  background: rgba(128, 0, 0, 0.4);
  transform: scale(1.1) rotate(180deg);
  box-shadow: 0 10px 20px rgba(128, 0, 0, 0.3);
}

.gallery2-modal-image-container {
  position: relative;
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 500px;
  background: rgba(0, 0, 0, 0.1);
}

.gallery2-modal-image {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 50px;
}

.gallery2-modal-image img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
  border-radius: 20px;
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
  transition: transform 0.3s ease;
}

.gallery2-modal-image img:hover {
  transform: scale(1.02);
}

.gallery2-modal-nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: linear-gradient(45deg, rgba(128, 0, 0, 0.3), rgba(0, 0, 0, 0.3));
  backdrop-filter: blur(15px);
  border: none;
  border-radius: 50%;
  width: 70px;
  height: 70px;
  color: white;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  z-index: 2001;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.gallery2-modal-nav:hover {
  background: linear-gradient(45deg, rgba(128, 0, 0, 0.5), rgba(0, 0, 0, 0.5));
  transform: translateY(-50%) scale(1.15);
  box-shadow: 0 15px 30px rgba(128, 0, 0, 0.4);
}

.gallery2-modal-prev {
  left: 25px;
}

.gallery2-modal-next {
  right: 25px;
}

.gallery2-modal-info {
  flex: 0 0 420px;
  padding: 50px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.02));
  backdrop-filter: blur(10px);
}

.gallery2-modal-category {
  color: #800000;
  font-size: 15px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 3px;
  margin-bottom: 15px;
  padding: 8px 16px;
  background: rgba(128, 0, 0, 0.1);
  border-radius: 25px;
  align-self: flex-start;
  border: 1px solid rgba(128, 0, 0, 0.2);
}

.gallery2-modal-title {
  color: white;
  font-size: 42px;
  font-weight: 900;
  margin-bottom: 25px;
  line-height: 1.1;
  text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
}

.gallery2-modal-description {
  color: rgba(255, 255, 255, 0.85);
  font-size: 17px;
  line-height: 1.7;
  margin-bottom: 30px;
  text-align: justify;
}

.gallery2-modal-actions {
  display: flex;
  gap: 18px;
  margin-bottom: 35px;
}

.gallery2-modal-btn {
  padding: 18px 28px;
  border: none;
  border-radius: 50px;
  font-weight: 700;
  font-size: 15px;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  cursor: pointer;
  transition: all 0.3s ease;
  flex: 1;
  position: relative;
  overflow: hidden;
}

.gallery2-modal-btn-primary {
  background: linear-gradient(45deg, #800000, #000000);
  color: white;
  box-shadow: 0 12px 24px rgba(128, 0, 0, 0.4);
}

.gallery2-modal-btn-primary:hover {
  transform: translateY(-4px);
  box-shadow: 0 18px 35px rgba(128, 0, 0, 0.5);
}

.gallery2-modal-counter {
  color: rgba(255, 255, 255, 0.7);
  font-size: 16px;
  font-weight: 600;
  text-align: center;
  text-transform: uppercase;
  letter-spacing: 3px;
  padding: 12px 24px;
  background: rgba(255, 255, 255, 0.05);
  border-radius: 25px;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Responsive Design */
@media (max-width: 768px) {
  .gallery2-page {
    padding: 20px 10px;
  }

  .main-title {
    font-size: 32px;
  }

  .card {
    width: 180px;
  }

  .card-info {
    min-width: 300px;
    padding: 1rem;
    bottom: 60px;
  }

  .card-info h2 {
    font-size: 28px;
  }

  .card-info p {
    font-size: 16px;
  }

  .close-btn {
    top: 20px;
    right: 20px;
    width: 50px;
    height: 50px;
  }

  .close-btn svg {
    width: 20px;
    height: 20px;
  }
}

@media (max-width: 480px) {
  .main-title {
    font-size: 28px;
  }

  .card {
    width: 140px;
  }

  .card-info {
    min-width: 280px;
    bottom: 40px;
  }

  .card-info h2 {
    font-size: 24px;
  }

  .card-info p {
    font-size: 14px;
  }
}

/* Responsive for Gallery2 Modal */
@media (max-width: 1024px) {
  .gallery2-modal-content {
    flex-direction: column;
    max-width: 95vw;
    max-height: 95vh;
  }

  .gallery2-modal-info {
    flex: none;
    padding: 35px;
  }

  .gallery2-modal-title {
    font-size: 32px;
  }

  .gallery2-modal-image-container {
    min-height: 400px;
  }
}

@media (max-width: 768px) {
  .gallery2-modal-info {
    padding: 30px;
  }

  .gallery2-modal-title {
    font-size: 28px;
  }

  .gallery2-modal-description {
    font-size: 15px;
  }

  .gallery2-modal-actions {
    flex-direction: column;
  }

  .gallery2-modal-nav {
    width: 60px;
    height: 60px;
  }

  .gallery2-modal-image-container {
    min-height: 350px;
  }

  .gallery2-modal-image {
    padding: 30px;
  }
}

@media (max-width: 480px) {
  .gallery2-modal-info {
    padding: 25px;
  }

  .gallery2-modal-title {
    font-size: 24px;
  }

  .gallery2-modal-description {
    font-size: 14px;
  }

  .gallery2-modal-close {
    top: 20px;
    right: 20px;
    width: 45px;
    height: 45px;
  }

  .gallery2-modal-nav {
    width: 50px;
    height: 50px;
  }

  .gallery2-modal-prev {
    left: 20px;
  }

  .gallery2-modal-next {
    right: 20px;
  }

  .gallery2-modal-btn {
    padding: 15px 22px;
    font-size: 13px;
  }
}
@endsection

@section('content')
<div class="gallery2-page">
  <!-- API Error Notification -->
  <div id="apiErrorNotification" class="fixed z-50 px-4 py-2 text-white bg-red-600 rounded-lg shadow-lg top-4 right-4" style="display: none;">
    <div class="flex items-center space-x-2">
      <span>⚠️</span>
      <span class="text-sm">API offline - Using cached data</span>
    </div>
  </div>

  <div class="header">
    <p class="subtitle"></p>
    <h1 class="main-title"></h1>
  </div>

  <div class="slider-container" id="sliderContainer">
    <div class="slider-track" id="sliderTrack">
      <!-- Gallery items will be loaded here via JavaScript -->
    </div>
  </div>

  <button class="close-btn" id="closeBtn">
    <svg viewBox="0 0 24 24" fill="none">
      <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" />
    </svg>
  </button>

  <div class="card-info" id="cardInfo">
    <h2 id="cardTitle"></h2>
    <p id="cardDesc"></p>
  </div>

  <!-- Modal Popup -->
  <div class="gallery2-modal-overlay" id="modalOverlay" style="display: none;">
    <div class="gallery2-modal-content" onclick="event.stopPropagation();">
      <button class="gallery2-modal-close" id="modalClose">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <div class="gallery2-modal-image-container">
        <button class="gallery2-modal-nav gallery2-modal-prev" id="modalPrev">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>

        <div class="gallery2-modal-image">
          <img id="modalImage" src="" alt="" class="object-contain w-full h-full" />
        </div>

        <button class="gallery2-modal-nav gallery2-modal-next" id="modalNext">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </div>

      <div class="gallery2-modal-info">
        <div class="gallery2-modal-category" id="modalCategory"></div>
        <h2 class="gallery2-modal-title" id="modalTitle"></h2>
        <p class="gallery2-modal-description" id="modalDescription"></p>
        <div class="gallery2-modal-actions">
          <button class="gallery2-modal-btn gallery2-modal-btn-primary" id="modalVisitBtn">
            Kunjungi
          </button>
        </div>
        <div class="gallery2-modal-counter" id="modalCounter">
          1 / 1
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load homepage constants and gallery items
    let GALLERY_ITEMS = [];
    let selectedItem = null;
    let currentIndex = 0;

    // Load data from API
    fetch('https://vanyadmin.progesio.my.id/api/vny/homepage/constants')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.data) {
                GALLERY_ITEMS = data.data.GALLERY_ITEMS || [];

                // Update header
                if (data.data.HERO_SECTION) {
                    document.querySelector('.subtitle').textContent = data.data.HERO_SECTION.SUBTITLE || 'The power of batak fashion';
                    document.querySelector('.main-title').textContent = data.data.HERO_SECTION.TITLE || 'Vany GROUP';
                }

                // Initialize slider
                initSlider();
            } else {
                // Show error notification
                document.getElementById('apiErrorNotification').style.display = 'block';
                // Load fallback data or show empty state
                console.warn('API offline, using fallback data');
                loadFallbackData();
            }
        })
        .catch(error => {
            console.error('Error loading homepage constants:', error);
            document.getElementById('apiErrorNotification').style.display = 'block';
            loadFallbackData();
        });

    function loadFallbackData() {
        // Fallback gallery items for when API is offline
        GALLERY_ITEMS = [
            {
                id: 1,
                title: "VNY Store",
                description: "Premium sneakers collection",
                image: "/images/vny-store.jpg",
                category: "Fashion",
                target: "/vny"
            },
            {
                id: 2,
                title: "Gallery",
                description: "Our latest collections",
                image: "/images/gallery.jpg",
                category: "Collections",
                target: "/gallery"
            },
            {
                id: 3,
                title: "About Us",
                description: "Learn more about VNY",
                image: "/images/about.jpg",
                category: "Company",
                target: "/about"
            }
        ];
        initSlider();
    }

    function initSlider() {
        if (!window.gsap || GALLERY_ITEMS.length === 0) {
            if (!window.gsap) console.warn('GSAP not loaded yet');
            if (GALLERY_ITEMS.length === 0) console.warn('No gallery items available');
            return;
        }

        // Store gallery data in window scope for CircularSlider access
        window.galleryData = GALLERY_ITEMS;

        // Create gallery cards
        const track = document.getElementById('sliderTrack');
        track.innerHTML = '';

        GALLERY_ITEMS.forEach((item, index) => {
            const card = document.createElement('div');
            card.className = 'card';
            card.setAttribute('data-title', item.title);
            card.setAttribute('data-desc', item.description);

            card.innerHTML = `
                <img src="${item.image}" alt="${item.title}" class="object-cover w-full h-full" onerror="this.src='/images/placeholder.jpg'">
                <div class="hover-overlay"><span>Click to see more</span></div>
            `;

            track.appendChild(card);
        });

        // Initialize GSAP CircularSlider
        new CircularSlider();
    }

    function handleItemClick(item, index) {
        selectedItem = item;
        currentIndex = index;
        showModal();
    }

    function showModal() {
        if (!selectedItem) return;

        const modal = document.getElementById('modalOverlay');
        const modalImage = document.getElementById('modalImage');
        const modalCategory = document.getElementById('modalCategory');
        const modalTitle = document.getElementById('modalTitle');
        const modalDescription = document.getElementById('modalDescription');
        const modalCounter = document.getElementById('modalCounter');
        const modalVisitBtn = document.getElementById('modalVisitBtn');

        modalImage.src = selectedItem.image;
        modalImage.alt = selectedItem.title;
        modalCategory.textContent = selectedItem.category;
        modalTitle.textContent = selectedItem.title;
        modalDescription.textContent = selectedItem.description;
        modalCounter.textContent = `${currentIndex + 1} / ${GALLERY_ITEMS.length}`;

        modalVisitBtn.onclick = () => {
            window.location.href = selectedItem.target;
        };

        modal.style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('modalOverlay').style.display = 'none';
        selectedItem = null;
    }

    function handleNext() {
        if (GALLERY_ITEMS.length === 0) return;
        currentIndex = (currentIndex + 1) % GALLERY_ITEMS.length;
        selectedItem = GALLERY_ITEMS[currentIndex];
        showModal();
    }

    function handlePrev() {
        if (GALLERY_ITEMS.length === 0) return;
        currentIndex = (currentIndex - 1 + GALLERY_ITEMS.length) % GALLERY_ITEMS.length;
        selectedItem = GALLERY_ITEMS[currentIndex];
        showModal();
    }

    // Modal event listeners
    document.getElementById('modalOverlay').addEventListener('click', closeModal);
    document.getElementById('modalClose').addEventListener('click', closeModal);
    document.getElementById('modalNext').addEventListener('click', handleNext);
    document.getElementById('modalPrev').addEventListener('click', handlePrev);

    // GSAP CircularSlider class (updated positions)
    const positions = [
        {
            height: 620,
            z: 220,
            rotateY: 48,
            y: 0,
            clip: "polygon(0px 0px, 100% 10%, 100% 90%, 0px 100%)"
        },
        {
            height: 580,
            z: 165,
            rotateY: 35,
            y: 0,
            clip: "polygon(0px 0px, 100% 8%, 100% 92%, 0px 100%)"
        },
        {
            height: 495,
            z: 110,
            rotateY: 15,
            y: 0,
            clip: "polygon(0px 0px, 100% 7%, 100% 93%, 0px 100%)"
        },
        {
            height: 420,
            z: 66,
            rotateY: 15,
            y: 0,
            clip: "polygon(0px 0px, 100% 7%, 100% 93%, 0px 100%)"
        },
        {
            height: 353,
            z: 46,
            rotateY: 6,
            y: 0,
            clip: "polygon(0px 0px, 100% 7%, 100% 93%, 0px 100%)"
        },
        {
            height: 310,
            z: 0,
            rotateY: 0,
            y: 0,
            clip: "polygon(0 0, 100% 0, 100% 100%, 0 100%)"
        },
        {
            height: 353,
            z: 54,
            rotateY: 348,
            y: 0,
            clip: "polygon(0px 7%, 100% 0px, 100% 100%, 0px 93%)"
        },
        {
            height: 420,
            z: 89,
            rotateY: -15,
            y: 0,
            clip: "polygon(0px 7%, 100% 0px, 100% 100%, 0px 93%)"
        },
        {
            height: 495,
            z: 135,
            rotateY: -15,
            y: 1,
            clip: "polygon(0px 7%, 100% 0px, 100% 100%, 0px 93%)"
        },
        {
            height: 580,
            z: 195,
            rotateY: 325,
            y: 0,
            clip: "polygon(0px 8%, 100% 0px, 100% 100%, 0px 92%)"
        },
        {
            height: 620,
            z: 240,
            rotateY: 312,
            y: 0,
            clip: "polygon(0px 10%, 100% 0px, 100% 100%, 0px 90%)"
        }
    ];

    class CircularSlider {
        constructor() {
            this.container = document.getElementById("sliderContainer");
            this.track = document.getElementById("sliderTrack");
            this.cards = Array.from(document.querySelectorAll(".card"));
            this.totalCards = this.cards.length;
            this.isDragging = false;
            this.startX = 0;
            this.dragDistance = 0;
            this.threshold = 60;
            this.processedSteps = 0;
            this.expandedCard = null;
            this.cardInfo = document.getElementById("cardInfo");
            this.cardTitle = document.getElementById("cardTitle");
            this.cardDesc = document.getElementById("cardDesc");
            this.closeBtn = document.getElementById("closeBtn");
            this.cardClone = null;

            this.init();
        }

        init() {
            this.applyPositions();
            this.attachEvents();
        }

        applyPositions() {
            this.cards.forEach((card, index) => {
                const pos = positions[index] || positions[positions.length - 1];
                gsap.set(card, {
                    height: pos.height,
                    clipPath: pos.clip,
                    transform: `translateZ(${pos.z}px) rotateY(${pos.rotateY}deg) translateY(${pos.y}px)`
                });
            });
        }

        expandCard(card) {
            if (this.expandedCard) return;

            // Use the existing modal system instead of custom card expansion
            const index = this.cards.indexOf(card);
            const galleryData = window.galleryData || [];
            const item = galleryData[index];

            if (item) {
                selectedItem = item;
                currentIndex = index;
                showModal();
            }
        }

        closeCard() {
            // Use the existing modal close function
            closeModal();
            this.expandedCard = null;
        }

        rotate(direction) {
            if (this.expandedCard) return;

            this.cards.forEach((card, index) => {
                let newIndex;
                if (direction === "next") {
                    newIndex = (index - 1 + this.totalCards) % this.totalCards;
                } else {
                    newIndex = (index + 1) % this.totalCards;
                }

                const pos = positions[newIndex] || positions[positions.length - 1];

                gsap.set(card, { clipPath: pos.clip });

                gsap.to(card, {
                    height: pos.height,
                    duration: 0.5,
                    ease: "power2.out"
                });

                gsap.to(card, {
                    transform: `translateZ(${pos.z}px) rotateY(${pos.rotateY}deg) translateY(${pos.y}px)`,
                    duration: 0.5,
                    ease: "power2.out"
                });
            });

            if (direction === "next") {
                const firstCard = this.cards.shift();
                this.cards.push(firstCard);
                this.track.appendChild(firstCard);
            } else {
                const lastCard = this.cards.pop();
                this.cards.unshift(lastCard);
                this.track.prepend(lastCard);
            }
        }

        attachEvents() {
            this.cards.forEach((card) => {
                card.addEventListener("click", (e) => {
                    if (!this.isDragging && !this.expandedCard) {
                        this.expandCard(card);
                    }
                });
            });

            this.closeBtn.addEventListener("click", () => this.closeCard());

            this.container.addEventListener("mousedown", (e) =>
                this.handleDragStart(e)
            );
            this.container.addEventListener(
                "touchstart",
                (e) => this.handleDragStart(e),
                { passive: false }
            );

            document.addEventListener("mousemove", (e) => this.handleDragMove(e));
            document.addEventListener("touchmove", (e) => this.handleDragMove(e), {
                passive: false
            });

            document.addEventListener("mouseup", () => this.handleDragEnd());
            document.addEventListener("touchend", () => this.handleDragEnd());

            document.addEventListener("keydown", (e) => {
                if (e.key === "Escape" && this.expandedCard) {
                    this.closeCard();
                } else if (e.key === "ArrowLeft" && !this.expandedCard) {
                    this.rotate("prev");
                } else if (e.key === "ArrowRight" && !this.expandedCard) {
                    this.rotate("next");
                }
            });
        }

        handleDragStart(e) {
            if (this.expandedCard) return;

            this.isDragging = true;
            this.container.classList.add("dragging");
            this.startX = e.type.includes("mouse") ? e.clientX : e.touches[0].clientX;
            this.dragDistance = 0;
            this.processedSteps = 0;
        }

        handleDragMove(e) {
            if (!this.isDragging) return;

            e.preventDefault();
            const currentX = e.type.includes("mouse")
                ? e.clientX
                : e.touches[0].clientX;
            this.dragDistance = currentX - this.startX;

            const steps = Math.floor(Math.abs(this.dragDistance) / this.threshold);

            if (steps > this.processedSteps) {
                const direction = this.dragDistance > 0 ? "prev" : "next";
                this.rotate(direction);
                this.processedSteps = steps;
            }
        }

        handleDragEnd() {
            if (!this.isDragging) return;

            this.isDragging = false;
            this.container.classList.remove("dragging");
        }
    }
});
</script>
@endsection
