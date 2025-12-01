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
          <div className="flex items-center">
            <div className="relative">
              <span className="absolute transform -translate-y-1/2 left-3 top-1/2">
                🔍
              </span>
              <input
                type="text"
                placeholder="Search"
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="py-1 pl-8 pr-4 text-white placeholder-white bg-transparent border-b border-white focus:outline-none focus:border-red-300"
              />
            </div>
          </div>

          {/* VNY Logo in center */}
          <div className="flex justify-center flex-1">
            <Link href="/vny" className="text-3xl font-bold tracking-wider">
              VNY
            </Link>
          </div>

          {/* Cart and Transaction on right */}
          <div className="flex items-center space-x-6">
            <Link href="/vny/cart" className="transition-colors hover:text-red-300">
              CART
            </Link>
            <Link href="/transactions" className="transition-colors hover:text-red-300">
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