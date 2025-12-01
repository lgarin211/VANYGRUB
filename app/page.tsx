'use client'

import { useEffect, useState, useCallback } from 'react'
import { useHomepageConstants } from '../hooks/useHomepageApi'
import { type GalleryItem } from '../lib/homepageApi'
import ApiLoading from '../components/ApiLoading'
import DynamicMetadata from '../components/DynamicMetadata'
import './home.css'

export default function Home() {
  const { constants, loading, error } = useHomepageConstants();
  const [selectedItem, setSelectedItem] = useState<GalleryItem | null>(null);
  const [currentIndex, setCurrentIndex] = useState(0);

  // Extract data with fallback
  const GALLERY_ITEMS = constants?.GALLERY_ITEMS || [];
  const ANIMATION = constants?.ANIMATION || { CAROUSEL_INTERVAL: 5000, TRANSITION_DURATION: 300 };
  const showApiError = error && GALLERY_ITEMS.length === 0;

  const handleItemClick = useCallback((item: GalleryItem, index: number) => {
    setSelectedItem(item);
    setCurrentIndex(index);
  }, []);

  const handleNext = useCallback(() => {
    if (GALLERY_ITEMS.length === 0) return;
    const nextIndex = (currentIndex + 1) % GALLERY_ITEMS.length;
    setSelectedItem(GALLERY_ITEMS[nextIndex]);
    setCurrentIndex(nextIndex);
  }, [currentIndex, GALLERY_ITEMS]);

  const handlePrev = useCallback(() => {
    if (GALLERY_ITEMS.length === 0) return;
    const prevIndex = (currentIndex - 1 + GALLERY_ITEMS.length) % GALLERY_ITEMS.length;
    setSelectedItem(GALLERY_ITEMS[prevIndex]);
    setCurrentIndex(prevIndex);
  }, [currentIndex, GALLERY_ITEMS]);

  const closeModal = useCallback(() => {
    setSelectedItem(null);
  }, []);

  // Initialize GSAP slider
  const initSlider = useCallback(() => {
    if (!window.gsap) return;

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
      container: HTMLElement | null;
      track: HTMLElement | null;
      cards: Element[];
      totalCards: number;
      isDragging: boolean;
      startX: number;
      dragDistance: number;
      threshold: number;
      processedSteps: number;
      expandedCard: Element | null;
      cardInfo: HTMLElement | null;
      cardTitle: HTMLElement | null;
      cardDesc: HTMLElement | null;
      closeBtn: HTMLElement | null;
      cardClone: HTMLElement | null;

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
        this.setInitialPositions();
        this.addEventListeners();
      }

      setInitialPositions() {
        this.cards.forEach((card, index) => {
          const position = positions[index % positions.length];
          const htmlCard = card as HTMLElement;
          
          window.gsap.set(htmlCard, {
            height: position.height,
            z: position.z,
            rotateY: position.rotateY,
            y: position.y,
            clipPath: position.clip,
          });
        });
      }

      addEventListeners() {
        if (this.container) {
          this.container.addEventListener("mousedown", this.startDrag.bind(this));
          this.container.addEventListener("touchstart", this.startDrag.bind(this), { passive: false });
          this.container.addEventListener("mousemove", this.drag.bind(this));
          this.container.addEventListener("touchmove", this.drag.bind(this), { passive: false });
          this.container.addEventListener("mouseup", this.endDrag.bind(this));
          this.container.addEventListener("touchend", this.endDrag.bind(this));
          this.container.addEventListener("mouseleave", this.endDrag.bind(this));
        }

        this.cards.forEach(card => {
          card.addEventListener("click", (e) => {
            e.preventDefault();
            if (!this.isDragging) {
              this.expandCard(card);
            }
          });
        });

        if (this.closeBtn) {
          this.closeBtn.addEventListener("click", this.closeCard.bind(this));
        }
      }

      startDrag(e: MouseEvent | TouchEvent) {
        this.isDragging = true;
        const clientX = e instanceof MouseEvent ? e.clientX : e.touches[0].clientX;
        this.startX = clientX;
        if (this.container) this.container.style.cursor = "grabbing";
      }

      drag(e: MouseEvent | TouchEvent) {
        if (!this.isDragging) return;
        
        e.preventDefault();
        const clientX = e instanceof MouseEvent ? e.clientX : e.touches[0].clientX;
        this.dragDistance = clientX - this.startX;
      }

      endDrag() {
        if (!this.isDragging) return;

        this.isDragging = false;
        if (this.container) this.container.style.cursor = "grab";

        if (Math.abs(this.dragDistance) > this.threshold) {
          const direction = this.dragDistance > 0 ? -1 : 1;
          this.rotateSlider(direction);
        }

        this.dragDistance = 0;
      }

      rotateSlider(direction: number) {
        const steps = Math.abs(direction);
        for (let i = 0; i < steps; i++) {
          if (direction > 0) {
            this.rotateForward();
          } else {
            this.rotateBackward();
          }
        }
      }

      rotateForward() {
        this.cards.forEach((card, index) => {
          const nextIndex = (index + 1) % positions.length;
          const position = positions[nextIndex];
          const htmlCard = card as HTMLElement;

          window.gsap.to(htmlCard, {
            duration: 0.6,
            height: position.height,
            z: position.z,
            rotateY: position.rotateY,
            y: position.y,
            clipPath: position.clip,
            ease: "power2.out"
          });
        });
      }

      rotateBackward() {
        this.cards.forEach((card, index) => {
          const prevIndex = (index - 1 + positions.length) % positions.length;
          const position = positions[prevIndex];
          const htmlCard = card as HTMLElement;

          window.gsap.to(htmlCard, {
            duration: 0.6,
            height: position.height,
            z: position.z,
            rotateY: position.rotateY,
            y: position.y,
            clipPath: position.clip,
            ease: "power2.out"
          });
        });
      }

      expandCard(card: Element) {
        if (this.expandedCard) return;

        const htmlCard = card as HTMLElement;
        
        // Find the corresponding gallery item
        const title = htmlCard.dataset.title;
        const galleryItem = GALLERY_ITEMS.find(item => item.title === title);
        const index = GALLERY_ITEMS.findIndex(item => item.title === title);
        
        if (galleryItem) {
          handleItemClick(galleryItem, index);
          return;
        }

        this.expandedCard = card;
        const desc = htmlCard.dataset.desc;

        if (this.cardTitle) this.cardTitle.textContent = title || '';
        if (this.cardDesc) this.cardDesc.textContent = desc || '';

        const rect = htmlCard.getBoundingClientRect();
        this.cardClone = htmlCard.cloneNode(true) as HTMLElement;
        this.cardClone.style.position = "fixed";
        this.cardClone.style.top = rect.top + "px";
        this.cardClone.style.left = rect.left + "px";
        this.cardClone.style.width = rect.width + "px";
        this.cardClone.style.height = rect.height + "px";
        this.cardClone.style.zIndex = "1000";
        this.cardClone.style.pointerEvents = "none";

        document.body.appendChild(this.cardClone);

        window.gsap.to(this.cardClone, {
          duration: 0.8,
          top: "50%",
          left: "50%",
          xPercent: -50,
          yPercent: -50,
          width: "80vw",
          height: "80vh",
          ease: "power2.out"
        });

        if (this.cardInfo) {
          window.gsap.to(this.cardInfo, {
            duration: 0.3,
            opacity: 1,
            display: "block",
            delay: 0.5
          });
        }
      }

      closeCard() {
        if (!this.expandedCard) return;

        if (this.cardInfo) {
          window.gsap.to(this.cardInfo, {
            duration: 0.3,
            opacity: 0,
            onComplete: () => {
              if (this.cardInfo) this.cardInfo.style.display = "none";
            }
          });
        }

        if (this.cardClone) {
          window.gsap.to(this.cardClone, {
            duration: 0.6,
            scale: 0,
            opacity: 0,
            ease: "power2.in",
            onComplete: () => {
              if (this.cardClone) {
                document.body.removeChild(this.cardClone);
                this.cardClone = null;
              }
            }
          });
        }

        this.expandedCard = null;
      }
    }

    // Initialize the slider
    new CircularSlider();
  }, [GALLERY_ITEMS, handleItemClick]);

  // Keyboard navigation - ALWAYS call hooks in the same order
  useEffect(() => {
    const handleKeyDown = (e: KeyboardEvent) => {
      if (!selectedItem) return;
      
      if (e.key === 'Escape') {
        closeModal();
      } else if (e.key === 'ArrowRight') {
        handleNext();
      } else if (e.key === 'ArrowLeft') {
        handlePrev();
      }
    };

    document.addEventListener('keydown', handleKeyDown);
    return () => document.removeEventListener('keydown', handleKeyDown);
  }, [selectedItem, handleNext, handlePrev, closeModal]);

  useEffect(() => {
    // Only initialize GSAP slider when we have gallery items and component is not loading
    if (loading || GALLERY_ITEMS.length === 0) return;

    // Check if GSAP is already loaded
    if (typeof window !== 'undefined' && window.gsap) {
      initSlider();
      return;
    }

    // Load GSAP script if not already loaded
    const existingScript = document.querySelector('script[src*="gsap"]');
    if (existingScript) {
      // GSAP already loading or loaded, wait for it
      if (window.gsap) {
        initSlider();
      } else {
        existingScript.addEventListener('load', initSlider);
        return () => existingScript.removeEventListener('load', initSlider);
      }
      return;
    }

    const script = document.createElement('script')
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js'
    script.onload = () => {
      initSlider();
    };

    document.head.appendChild(script);

    return () => {
      document.head.removeChild(script);
    };
  }, [loading, GALLERY_ITEMS, initSlider]);


    document.head.appendChild(script);

    return () => {
      document.head.removeChild(script);
    };
  }, []);

  // Loading state - after all hooks are called
  if (loading) {
    return <ApiLoading message="Loading VANYGRUB..." color="border-red-500" />;
  }

  // Error state - show warning if API failed
  if (error) {
    console.warn('API error, using fallback data:', error);
  }

  return (
    <div className="gallery2-page">
      <DynamicMetadata />
      
      {/* API Error Notification */}
      {showApiError && (
        <div className="fixed top-4 right-4 z-50 bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg">
          <div className="flex items-center space-x-2">
            <span>⚠️</span>
            <span className="text-sm">API offline - Using cached data</span>
          </div>
        </div>
      )}
      
      <div className="header">
        <p className="subtitle">{constants?.HERO_SECTION?.SUBTITLE || 'The power of batak fashion'}</p>
        <h1 className="main-title">{constants?.HERO_SECTION?.TITLE || 'Vany GROUP'}</h1>
      </div>

      <div className="slider-container" id="sliderContainer">
        <div className="slider-track" id="sliderTrack">
          {GALLERY_ITEMS.map((item) => (
            <div key={item.id} className="card" data-title={item.title} data-desc={item.description}>
              <img src={item.image} alt={item.title} />
              <div className="hover-overlay"><span>Click to see more</span></div>
            </div>
          ))}
        </div>
      </div>

      <button className="close-btn" id="closeBtn">
        <svg viewBox="0 0 24 24" fill="none">
          <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" />
        </svg>
      </button>

      <div className="card-info" id="cardInfo">
        <h2 id="cardTitle"></h2>
        <p id="cardDesc"></p>
      </div>

      {/* Modal Popup */}
      {selectedItem && (
        <div className="gallery2-modal-overlay" onClick={closeModal}>
          <div className="gallery2-modal-content" onClick={(e) => e.stopPropagation()}>
            <button className="gallery2-modal-close" onClick={closeModal}>
              <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
            
            <div className="gallery2-modal-image-container">
              <button className="gallery2-modal-nav gallery2-modal-prev" onClick={handlePrev}>
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                </svg>
              </button>
              
              <div className="gallery2-modal-image">
                <img src={selectedItem.image} alt={selectedItem.title} />
              </div>
              
              <button className="gallery2-modal-nav gallery2-modal-next" onClick={handleNext}>
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                </svg>
              </button>
            </div>
            
            <div className="gallery2-modal-info">
              <div className="gallery2-modal-category">{selectedItem.category}</div>
              <h2 className="gallery2-modal-title">{selectedItem.title}</h2>
              <p className="gallery2-modal-description">{selectedItem.description}</p>
              <div className="gallery2-modal-actions">
                <button 
                  className="gallery2-modal-btn gallery2-modal-btn-primary"
                  onClick={() => {
                    window.location.href = selectedItem.target;
                  }}
                >
                  Kunjungi
                </button>
              </div>
              <div className="gallery2-modal-counter">
                {currentIndex + 1} / {GALLERY_ITEMS.length}
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}