'use client'

import { useEffect, useState } from 'react'
import './gallery2.css'

export default function Gallery2() {
  const [selectedItem, setSelectedItem] = useState<any>(null);
  const [currentIndex, setCurrentIndex] = useState(0);

  const galleryItems = [
    {
      id: 1,
      title: 'Vany Songket',
      image: 'https://images.unsplash.com/photo-1546548970-71785318a17b?w=600&h=800&fit=crop',
      description: 'Koleksi songket tradisional dengan desain modern yang memadukan kearifan lokal dengan gaya kontemporer. Dibuat dengan benang emas dan perak berkualitas tinggi.',
      price: 'Rp 2.500.000',
      category: 'Traditional Fashion'
    },
    {
      id: 2,
      title: 'Vny Toba Shoes',
      image: 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=600&h=800&fit=crop',
      description: 'Sepatu berkualitas tinggi dengan desain yang nyaman dan gaya yang elegan untuk aktivitas sehari-hari. Terbuat dari kulit asli premium.',
      price: 'Rp 1.800.000',
      category: 'Footwear'
    },
    {
      id: 3,
      title: 'Vany Villa',
      image: 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=600&h=800&fit=crop',
      description: 'Villa mewah dengan pemandangan indah dan fasilitas lengkap untuk liburan yang tak terlupakan. Dilengkapi dengan kolam renang pribadi.',
      price: 'Rp 15.000.000/malam',
      category: 'Hospitality'
    },
    {
      id: 4,
      title: 'Vany Apartement',
      image: 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=600&h=800&fit=crop',
      description: 'Apartemen modern dengan lokasi strategis dan fasilitas premium untuk hunian yang nyaman. Dilengkapi dengan gym dan rooftop garden.',
      price: 'Rp 5.500.000.000',
      category: 'Real Estate'
    },
    {
      id: 5,
      title: 'Vany Shalon',
      image: 'https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=600&h=800&fit=crop',
      description: 'Salon kecantikan dengan layanan profesional dan perawatan terbaik untuk penampilan yang memukau. Treatment dengan produk premium.',
      price: 'Mulai Rp 350.000',
      category: 'Beauty & Wellness'
    },
    {
      id: 6,
      title: 'Vany Butik',
      image: 'https://images.unsplash.com/photo-1611930022073-b7a4ba5fcccd?w=600&h=800&fit=crop',
      description: 'Butik fashion dengan koleksi pakaian trendy dan berkualitas untuk gaya hidup modern. Desain eksklusif dari designer ternama.',
      price: 'Mulai Rp 850.000',
      category: 'Fashion Retail'
    },
    {
      id: 7,
      title: 'Vany Cafe',
      image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=800&fit=crop',
      description: 'Cafe dengan suasana cozy dan menu yang lezat untuk tempat berkumpul dan bersantai.',
      price: 'Mulai Rp 25.000',
      category: 'Food & Beverage'
    },
    {
      id: 8,
      title: 'Vany Store',
      image: 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=600&h=800&fit=crop',
      description: 'Toko retail dengan berbagai produk kebutuhan sehari-hari dan barang-barang berkualitas.',
      price: 'Mulai Rp 15.000',
      category: 'Retail Store'
    },
    {
      id: 9,
      title: 'Vany Tech',
      image: 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=600&h=800&fit=crop',
      description: 'Layanan teknologi dan gadget dengan produk terdepan untuk kebutuhan digital modern.',
      price: 'Mulai Rp 500.000',
      category: 'Technology'
    },
    {
      id: 10,
      title: 'Vany Wellness',
      image: 'https://images.unsplash.com/photo-1610701596007-11502861dcfa?w=600&h=800&fit=crop',
      description: 'Pusat kesehatan dan kebugaran dengan program holistik untuk hidup yang sehat dan seimbang.',
      price: 'Mulai Rp 150.000',
      category: 'Health & Fitness'
    },
    {
      id: 11,
      title: 'Vany Home Decor',
      image: 'https://images.unsplash.com/photo-1564584217132-2271feaeb3c5?w=600&h=800&fit=crop',
      description: 'Dekorasi rumah dengan sentuhan kontemporer dan Skandinavia untuk hunian yang indah.',
      price: 'Mulai Rp 250.000',
      category: 'Home & Living'
    }
  ];

  const handleItemClick = (item: any, index: number) => {
    setSelectedItem(item);
    setCurrentIndex(index);
  };

  const handleNext = () => {
    const nextIndex = (currentIndex + 1) % galleryItems.length;
    setSelectedItem(galleryItems[nextIndex]);
    setCurrentIndex(nextIndex);
  };

  const handlePrev = () => {
    const prevIndex = (currentIndex - 1 + galleryItems.length) % galleryItems.length;
    setSelectedItem(galleryItems[prevIndex]);
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

          expandCard(card) {
            if (this.expandedCard) return;

            // Find the corresponding gallery item
            const title = card.dataset.title;
            const galleryItem = galleryItems.find(item => item.title === title);
            const index = galleryItems.findIndex(item => item.title === title);
            
            if (galleryItem) {
              handleItemClick(galleryItem, index);
              return;
            }

            this.expandedCard = card;
            const desc = card.dataset.desc;

            this.cardTitle.textContent = title;
            this.cardDesc.textContent = desc;

            const rect = card.getBoundingClientRect();
            const clone = card.cloneNode(true);
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
            this.track.classList.add("blurred");

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
                this.cardInfo.classList.add("visible");
                this.closeBtn.classList.add("visible");
              }
            });
          }

          closeCard() {
            if (!this.expandedCard) return;

            this.cardInfo.classList.remove("visible");
            this.closeBtn.classList.remove("visible");

            const card = this.expandedCard;
            const clone = this.cardClone;
            const rect = card.getBoundingClientRect();
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
                clone.remove();
                gsap.set(card, { opacity: 1 });
                this.track.classList.remove("blurred");
                this.expandedCard = null;
                this.cardClone = null;
              }
            });
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
        <p className="subtitle">The powerr of batak fasion</p>
        <h1 className="main-title">Vany GROUP</h1>
      </div>

      <div className="slider-container" id="sliderContainer">
        <div className="slider-track" id="sliderTrack">
          {galleryItems.map((item) => (
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
              <div className="gallery2-modal-price">{selectedItem.price}</div>
              <div className="gallery2-modal-actions">
                <button className="gallery2-modal-btn gallery2-modal-btn-primary">
                  Lihat Detail
                </button>
                <button className="gallery2-modal-btn gallery2-modal-btn-secondary">
                  Hubungi Kami
                </button>
              </div>
              <div className="gallery2-modal-counter">
                {currentIndex + 1} / {galleryItems.length}
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}