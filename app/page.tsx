'use client'

import { useEffect, useState } from 'react'
import { HOMEPAGE_CONSTANTS, type GalleryItem } from '../constants'
import './home.css'

export default function Home() {
  const [selectedItem, setSelectedItem] = useState<GalleryItem | null>(null);
  const [currentIndex, setCurrentIndex] = useState(0);

  const { GALLERY_ITEMS, ANIMATION } = HOMEPAGE_CONSTANTS;

  const handleItemClick = (item: GalleryItem, index: number) => {
    setSelectedItem(item);
    setCurrentIndex(index);
  };

  const handleNext = () => {
    const nextIndex = (currentIndex + 1) % GALLERY_ITEMS.length;
    setSelectedItem(GALLERY_ITEMS[nextIndex]);
    setCurrentIndex(nextIndex);
  };

  const handlePrev = () => {
    const prevIndex = (currentIndex - 1 + GALLERY_ITEMS.length) % GALLERY_ITEMS.length;
    setSelectedItem(GALLERY_ITEMS[prevIndex]);
    setCurrentIndex(prevIndex);
  };

  const closeModal = () => {
    setSelectedItem(null);
  };

  // Keyboard navigation
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
  }, [selectedItem, currentIndex]);

  useEffect(() => {
    // Load GSAP script
    const script = document.createElement('script')
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js'
    script.onload = () => {
      // Initialize the slider after GSAP is loaded
      const initSlider = () => {
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
            this.applyPositions();
            this.attachEvents();
          }

          applyPositions() {
            this.cards.forEach((card, index) => {
              const pos = positions[index];
              gsap.set(card, {
                height: pos.height,
                clipPath: pos.clip,
                transform: `translateZ(${pos.z}px) rotateY(${pos.rotateY}deg) translateY(${pos.y}px)`
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
            const clone = htmlCard.cloneNode(true) as HTMLElement;
            const overlay = clone.querySelector(".hover-overlay");
            if (overlay) overlay.remove();

            clone.style.position = "fixed";
            clone.style.left = rect.left + "px";
            clone.style.top = rect.top + "px";
            clone.style.width = rect.width + "px";
            clone.style.height = rect.height + "px";
            clone.style.margin = "0";
            clone.style.zIndex = "1000";
            clone.classList.add("clone");

            document.body.appendChild(clone);
            this.cardClone = clone;

            gsap.set(card, { opacity: 0 });
            if (this.track) this.track.classList.add("blurred");

            const maxHeight = window.innerHeight * 0.8;
            const finalWidth = 500;
            const finalHeight = Math.min(650, maxHeight);
            const centerX = window.innerWidth / 2;
            const centerY = window.innerHeight / 2;

            gsap.to(clone, {
              width: finalWidth,
              height: finalHeight,
              left: centerX - finalWidth / 2,
              top: centerY - finalHeight / 2,
              clipPath: "polygon(0 0, 100% 0, 100% 100%, 0 100%)",
              transform: "translateZ(0) rotateY(0deg)",
              duration: 0.8,
              ease: "power2.out",
              onComplete: () => {
                if (this.cardInfo) this.cardInfo.classList.add("visible");
                if (this.closeBtn) this.closeBtn.classList.add("visible");
              }
            });
          }

          closeCard() {
            if (!this.expandedCard) return;

            if (this.cardInfo) this.cardInfo.classList.remove("visible");
            if (this.closeBtn) this.closeBtn.classList.remove("visible");

            const card = this.expandedCard;
            const clone = this.cardClone;
            
            if (!card || !clone) return;
            
            const htmlCard = card as HTMLElement;
            const rect = htmlCard.getBoundingClientRect();
            const index = this.cards.indexOf(card);
            const pos = positions[index];

            gsap.to(clone, {
              width: rect.width,
              height: rect.height,
              left: rect.left,
              top: rect.top,
              clipPath: pos.clip,
              duration: 0.8,
              ease: "power2.out",
              onComplete: () => {
                if (clone) clone.remove();
                gsap.set(card, { opacity: 1 });
                if (this.track) this.track.classList.remove("blurred");
                this.expandedCard = null;
                this.cardClone = null;
              }
            });
          }

          rotate(direction: string) {
            if (this.expandedCard) return;

            this.cards.forEach((card, index) => {
              let newIndex;
              if (direction === "next") {
                newIndex = (index - 1 + this.totalCards) % this.totalCards;
              } else {
                newIndex = (index + 1) % this.totalCards;
              }

              const pos = positions[newIndex];

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
              if (firstCard) {
                this.cards.push(firstCard);
                if (this.track) this.track.appendChild(firstCard);
              }
            } else {
              const lastCard = this.cards.pop();
              if (lastCard) {
                this.cards.unshift(lastCard);
                if (this.track) this.track.prepend(lastCard);
              }
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

            if (this.closeBtn) {
              this.closeBtn.addEventListener("click", () => this.closeCard());
            }

            if (this.container) {
              this.container.addEventListener("mousedown", (e) =>
                this.handleDragStart(e)
              );
              this.container.addEventListener(
                "touchstart",
                (e) => this.handleDragStart(e),
                { passive: false }
              );
            }

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

          handleDragStart(e: MouseEvent | TouchEvent) {
            if (this.expandedCard) return;

            this.isDragging = true;
            if (this.container) this.container.classList.add("dragging");
            this.startX = e.type.includes("mouse") ? (e as MouseEvent).clientX : (e as TouchEvent).touches[0].clientX;
            this.dragDistance = 0;
            this.processedSteps = 0;
          }

          handleDragMove(e: MouseEvent | TouchEvent) {
            if (!this.isDragging) return;

            e.preventDefault();
            const currentX = e.type.includes("mouse")
              ? (e as MouseEvent).clientX
              : (e as TouchEvent).touches[0].clientX;
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
            if (this.container) this.container.classList.remove("dragging");
          }
        }

        new CircularSlider();
      };

      initSlider();
    };

    document.head.appendChild(script);

    return () => {
      document.head.removeChild(script);
    };
  }, []);

  return (
    <div className="gallery2-page">
      <div className="header">
        <p className="subtitle">The power of batak fashion</p>
        <h1 className="main-title">Vany GROUP</h1>
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