'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import '../../styles/gallery.css';

const Gallery: React.FC = () => {
  const [selectedItem, setSelectedItem] = useState<any>(null);
  const [currentIndex, setCurrentIndex] = useState(0);

  const galleryItems = [
    {
      id: 1,
      title: 'Vany Songket',
      image: '/temp/nike-just-do-it(6).jpg',
      description: 'Koleksi songket tradisional dengan desain modern yang memadukan kearifan lokal dengan gaya kontemporer. Dibuat dengan benang emas dan perak berkualitas tinggi.',
      price: 'Rp 2.500.000',
      category: 'Traditional Fashion'
    },
    {
      id: 2,
      title: 'Vny Toba Shoes',
      image: '/temp/nike-just-do-it(7).jpg',
      description: 'Sepatu berkualitas tinggi dengan desain yang nyaman dan gaya yang elegan untuk aktivitas sehari-hari. Terbuat dari kulit asli premium.',
      price: 'Rp 1.800.000',
      category: 'Footwear'
    },
    {
      id: 3,
      title: 'Vany Villa',
      image: '/temp/nike-just-do-it(8).jpg',
      description: 'Villa mewah dengan pemandangan indah dan fasilitas lengkap untuk liburan yang tak terlupakan. Dilengkapi dengan kolam renang pribadi.',
      price: 'Rp 15.000.000/malam',
      category: 'Hospitality'
    },
    {
      id: 4,
      title: 'Vany Apartement',
      image: '/temp/nike-just-do-it(9).jpg',
      description: 'Apartemen modern dengan lokasi strategis dan fasilitas premium untuk hunian yang nyaman. Dilengkapi dengan gym dan rooftop garden.',
      price: 'Rp 5.500.000.000',
      category: 'Real Estate'
    },
    {
      id: 5,
      title: 'Vany Shalon',
      image: '/temp/nike-just-do-it(10).jpg',
      description: 'Salon kecantikan dengan layanan profesional dan perawatan terbaik untuk penampilan yang memukau. Treatment dengan produk premium.',
      price: 'Mulai Rp 350.000',
      category: 'Beauty & Wellness'
    },
    {
      id: 6,
      title: 'Vany Butik',
      image: '/temp/nike-just-do-it(11).jpg',
      description: 'Butik fashion dengan koleksi pakaian trendy dan berkualitas untuk gaya hidup modern. Desain eksklusif dari designer ternama.',
      price: 'Mulai Rp 850.000',
      category: 'Fashion Retail'
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
  React.useEffect(() => {
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

  return (
    <div className="gallery-container">
      {/* Header */}
      <header className="gallery-header">
        <div className="container px-4 py-6 mx-auto">
          <div className="flex items-center justify-between">
            <Link href="/" className="text-3xl font-bold text-white">
              VNY
            </Link>
            <nav className="hidden space-x-8 md:flex">
              <Link href="/" className="text-white transition-colors hover:text-red-300">HOME</Link>
              <Link href="/vny/product" className="text-white transition-colors hover:text-red-300">PRODUCT</Link>
              <Link href="/about" className="text-white transition-colors hover:text-red-300">ABOUT VNY</Link>
              <Link href="/gallery" className="pb-1 text-white border-b-2 border-white">GALLERY</Link>
            </nav>
          </div>
        </div>
      </header>

      {/* Main Gallery */}
      <main className="gallery-main">
        <div className="gallery-title-section">
          <h1 className="gallery-main-title">VANY GRUB</h1>
          <p className="gallery-subtitle">Discover Our Amazing Collection</p>
        </div>

        {/* Diamond Gallery Layout */}
        <div className="diamond-gallery">
          {/* First Row - 3 images */}
          <ul className="first">
            {galleryItems.slice(0, 3).map((item, index) => (
              <li key={item.id} className="gallery-item" style={{'--delay': `${index * 0.2}s`} as React.CSSProperties}>
                <figure className="image" onClick={() => handleItemClick(item, index)}>
                  <img src={item.image} alt={item.title} />
                  <figcaption>{item.title}</figcaption>
                </figure>
              </li>
            ))}
          </ul>

          {/* Second Row - 2 images */}
          <ul className="second">
            {galleryItems.slice(3, 5).map((item, index) => (
              <li key={item.id} className="gallery-item" style={{'--delay': `${(index + 3) * 0.2}s`} as React.CSSProperties}>
                <figure className="image" onClick={() => handleItemClick(item, index + 3)}>
                  <img src={item.image} alt={item.title} />
                  <figcaption>{item.title}</figcaption>
                </figure>
              </li>
            ))}
          </ul>

          {/* Third Row - 1 image */}
          <ul className="third">
            <li className="gallery-item" style={{'--delay': '1.0s'} as React.CSSProperties}>
              <figure className="image" onClick={() => handleItemClick(galleryItems[5], 5)}>
                <img src={galleryItems[5].image} alt={galleryItems[5].title} />
                <figcaption>{galleryItems[5].title}</figcaption>
              </figure>
            </li>
          </ul>
        </div>
      </main>

      {/* Modal Popup */}
      {selectedItem && (
        <div className="gallery-modal-overlay" onClick={closeModal}>
          <div className="gallery-modal-content" onClick={(e) => e.stopPropagation()}>
            <button className="gallery-modal-close" onClick={closeModal}>
              <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
            
            <div className="gallery-modal-image-container">
              <button className="gallery-modal-nav gallery-modal-prev" onClick={handlePrev}>
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                </svg>
              </button>
              
              <div className="gallery-modal-image">
                <img src={selectedItem.image} alt={selectedItem.title} />
              </div>
              
              <button className="gallery-modal-nav gallery-modal-next" onClick={handleNext}>
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                </svg>
              </button>
            </div>
            
            <div className="gallery-modal-info">
              <div className="gallery-modal-category">{selectedItem.category}</div>
              <h2 className="gallery-modal-title">{selectedItem.title}</h2>
              <p className="gallery-modal-description">{selectedItem.description}</p>
              <div className="gallery-modal-price">{selectedItem.price}</div>
              <div className="gallery-modal-actions">
                <button className="gallery-modal-btn gallery-modal-btn-primary">
                  Lihat Detail
                </button>
                <button className="gallery-modal-btn gallery-modal-btn-secondary">
                  Hubungi Kami
                </button>
              </div>
              <div className="gallery-modal-counter">
                {currentIndex + 1} / {galleryItems.length}
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Footer */}
      <footer className="gallery-footer">
        <div className="container px-4 py-8 mx-auto">
          <div className="text-center">
            <h3 className="mb-4 text-2xl font-bold text-white">VNY</h3>
            <p className="mb-6 text-white/70">Premium fashion and lifestyle products</p>
            <div className="flex justify-center space-x-6">
              <Link href="/" className="text-white transition-colors hover:text-red-300">Home</Link>
              <Link href="/vny/product" className="text-white transition-colors hover:text-red-300">Products</Link>
              <Link href="/about" className="text-white transition-colors hover:text-red-300">About</Link>
              <Link href="/gallery" className="text-white transition-colors hover:text-red-300">Gallery</Link>
            </div>
          </div>
        </div>
      </footer>
    </div>
  );
};

export default Gallery;