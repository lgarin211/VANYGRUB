'use client';

import React from 'react';
import Image from 'next/image';
import { useHomeData } from '../hooks/useApi';

interface SpecialOfferProps {
  title?: string;
}

const SpecialOffer: React.FC<SpecialOfferProps> = ({ title }) => {
  // Load data from API with fallback
  const { data: homeData, loading } = useHomeData();
  const { title: defaultTitle, subtitle, offers: specialOffers, cardConfig } = homeData?.specialOffers || {
    title: "Special Offers",
    subtitle: "Don't miss out on these amazing deals",
    offers: [],
    cardConfig: { showDiscount: true }
  };
  const displayTitle = title || defaultTitle;

  if (loading) {
    return (
      <section className="bg-white py-16">
        <div className="container mx-auto px-4">
          <div className="text-center">Loading special offers...</div>
        </div>
      </section>
    );
  }
  
  return (
    <section className="bg-white py-16">
      <div className="container mx-auto px-4">
        {/* Section Header */}
        <div className="text-center mb-12">
          <h2 className="text-4xl font-bold text-gray-900 mb-4">{displayTitle}</h2>
          <p className="text-gray-600 text-lg max-w-2xl mx-auto">
            {subtitle}
          </p>
        </div>

        {/* Special Offers Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-7xl mx-auto mb-8">
          {specialOffers.map((offer, index) => (
            <div
              key={offer.id}
              className={`relative rounded-2xl overflow-hidden group cursor-pointer transform hover:scale-[${cardConfig.hoverScaleFactor}] transition-all duration-[${cardConfig.transitionDuration}] shadow-lg hover:shadow-2xl`}
              style={{ height: `${cardConfig.height}px` }}
              data-aos="fade-up"
              data-aos-delay={`${index * 100}`}
            >
              {/* Product Image */}
              <div className="absolute inset-0">
                <Image
                  src={offer.image}
                  alt={offer.title}
                  fill
                  className="object-cover group-hover:scale-110 transition-transform duration-700"
                  sizes="(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw"
                />
              </div>

              {/* Gradient Overlay */}
              <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-60 group-hover:opacity-40 transition-opacity duration-500"></div>

              {/* Content */}
              <div className="absolute bottom-0 left-0 right-0 p-6 z-10">
                <div className="text-white/90 text-sm font-medium mb-2 tracking-wider uppercase">
                  {offer.brand}
                </div>
                <h3 className="text-white text-xl font-bold mb-4 leading-tight">
                  {offer.title}
                </h3>
                
                {/* Coming Soon Badge */}
                <div className="inline-flex items-center justify-center">
                  <span className="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-semibold border border-white/30 hover:bg-white/30 transition-all duration-300">
                    {offer.status}
                  </span>
                </div>
              </div>

              {/* Corner Accent */}
              <div className="absolute top-4 right-4 w-3 h-3 bg-white/40 rounded-full group-hover:bg-white/60 transition-colors duration-500"></div>
              <div className="absolute top-8 right-6 w-2 h-2 bg-white/20 rounded-full group-hover:bg-white/40 transition-colors duration-500"></div>

              {/* Side Accent Line */}
              <div className="absolute left-0 top-1/2 transform -translate-y-1/2 w-1 h-16 bg-gradient-to-b from-white/40 to-transparent group-hover:h-20 transition-all duration-500"></div>
            </div>
          ))}
        </div>

        {/* Call to Action */}
        <div className="text-center mt-4">
          <button className="bg-gray-900 text-white px-8 py-4 rounded-full text-lg font-bold hover:bg-gray-800 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
            View All Special Offers
          </button>
        </div>
      </div>
    </section>
  );
};

export default SpecialOffer;