'use client';

import React, { useState } from 'react';
import Link from 'next/link';

const Header: React.FC = () => {
  const [isSearchOpen, setIsSearchOpen] = useState(false);

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
            VNY
          </div>

          {/* Right menu */}
          <div className="flex items-center space-x-6">
            <Link href="/cart" className="hover:text-red-200 transition-colors">
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
            <Link 
              href="/" 
              className="text-white border-b-2 border-white pb-1 hover:text-red-200 transition-colors"
            >
              HOME
            </Link>
            <Link 
              href="/product" 
              className="text-white/80 hover:text-white pb-1 transition-colors"
            >
              PRODUCT
            </Link>
            <Link 
              href="/about" 
              className="text-white/80 hover:text-white pb-1 transition-colors"
            >
              ABOUT VNY
            </Link>
            <Link 
              href="/gallery" 
              className="text-white/80 hover:text-white pb-1 transition-colors"
            >
              GALLERY
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