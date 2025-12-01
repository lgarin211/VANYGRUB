'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { useSiteConfig } from '../hooks/useHomepageApi';

const Header: React.FC = () => {
  const [isSearchOpen, setIsSearchOpen] = useState(false);
  const { config } = useSiteConfig();

  const siteName = config?.site_name || 'VNY';
  const mainMenu = config?.navigation?.main_menu || [
    { label: 'Home', url: '/', active: true },
    { label: 'VNY Products', url: '/vny', active: false },
    { label: 'Gallery', url: '/gallery', active: false },
    { label: 'About', url: '/about', active: false },
    { label: 'Transactions', url: '/transactions', active: false }
  ];

  return (
    <header className="bg-red-800 text-white">
      <div className="container mx-auto px-4">
        {/* Top bar */}
        <div className="flex items-center justify-between py-4">
          {/* Search */}
          <div className="flex items-center space-x-4">
            <button 
              onClick={() => setIsSearchOpen(!isSearchOpen)}
              className="flex items-center space-x-2 border-b border-white/30 pb-1"
            >
              <span className="text-sm">🔍 Search</span>
            </button>
          </div>

          {/* Logo */}
          <div className="text-4xl font-bold tracking-widest">
            {siteName}
          </div>

          {/* Right menu */}
          <div className="flex items-center space-x-6">
            <Link href="/vny/cart" className="hover:text-red-200 transition-colors">
              CART
            </Link>
            <Link href="/transaction" className="hover:text-red-200 transition-colors">
              TRANSACTION
            </Link>
          </div>
        </div>

        {/* Navigation */}
        <nav className="border-t border-white/20">
          <div className="flex items-center justify-center space-x-12 py-4">
            {mainMenu.map((menuItem) => (
              <Link 
                key={menuItem.url}
                href={menuItem.url} 
                className={`pb-1 transition-colors ${
                  menuItem.active 
                    ? 'text-white border-b-2 border-white hover:text-red-200' 
                    : 'text-white/80 hover:text-white'
                }`}
              >
                {menuItem.label.toUpperCase()}
              </Link>
            ))}
            </Link>
          </div>
        </nav>
      </div>

      {/* Search Dropdown */}
      {isSearchOpen && (
        <div className="bg-red-700 border-t border-red-600">
          <div className="container mx-auto px-4 py-4">
            <input
              type="text"
              placeholder="Search products..."
              className="w-full max-w-md px-4 py-2 bg-white/10 border border-white/20 rounded text-white placeholder-white/60 focus:outline-none focus:border-white/40"
            />
          </div>
        </div>
      )}
    </header>
  );
};

export default Header;