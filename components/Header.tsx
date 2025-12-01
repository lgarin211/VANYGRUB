'use client';

import { useState } from 'react';
import Link from 'next/link';
import { useSiteConfig } from '../hooks/useHomepageApi';

export default function Header() {
  const [searchQuery, setSearchQuery] = useState('');
  
  const { config: siteConfig } = useSiteConfig();

  const navItems = [
    { name: 'HOME', href: '/vny' },
    { name: 'PRODUCT', href: '/vny/product' },
    { name: 'ABOUT VNY', href: '/about' },
    { name: 'GALLERY', href: '/' }
  ];

  return (
    <header className="text-white bg-red-800">
      {/* Top bar with search, logo, and actions */}
      <div className="container px-4 mx-auto">
        <div className="flex items-center justify-between py-3">
          {/* Search bar on left */}
          <div className="flex items-center flex-shrink-0">
            <div className="relative">
              <span className="absolute transform -translate-y-1/2 left-2 md:left-3 top-1/2 text-sm">
                🔍
              </span>
              <input
                type="text"
                placeholder="Search"
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="w-16 md:w-32 py-1 pl-6 md:pl-8 pr-2 md:pr-4 text-sm md:text-base text-white placeholder-white bg-transparent border-b border-white focus:outline-none focus:border-red-300"
              />
            </div>
          </div>

          {/* VNY Logo in center */}
          <div className="flex justify-center flex-1 mx-2 md:mx-4">
            <Link href="/vny" className="text-2xl md:text-3xl font-bold tracking-wider">
              VNY
            </Link>
          </div>

          {/* Cart and Transaction on right */}
          <div className="flex items-center space-x-2 md:space-x-6 flex-shrink-0">
            <Link href="/vny/cart" className="text-xs md:text-base transition-colors hover:text-red-300">
              CART
            </Link>
            <Link href="/transactions" className="text-xs md:text-base transition-colors hover:text-red-300">
              TRANSACTION
            </Link>
          </div>
        </div>

        {/* Navigation menu below */}
        <div className="border-t border-red-700">
          <nav className="flex justify-center py-3 space-x-8">
            {navItems.map((item) => (
              <Link
                key={item.name}
                href={item.href}
                className="font-medium transition-colors hover:text-red-300"
              >
                {item.name}
              </Link>
            ))}
          </nav>
        </div>
      </div>
    </header>
  );
}