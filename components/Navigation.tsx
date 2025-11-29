import React from 'react';
import Link from 'next/link';

const Navigation: React.FC = () => {
  return (
    <nav className="bg-orange-600 text-white">
      <div className="container mx-auto px-4">
        <div className="flex items-center justify-between py-4">
          <Link href="/" className="text-2xl font-bold">
            VanyGrub
          </Link>
          <div className="hidden md:flex space-x-6">
            <Link href="/" className="hover:text-orange-200">
              Home
            </Link>
            <Link href="/menu" className="hover:text-orange-200">
              Menu
            </Link>
            <Link href="/about" className="hover:text-orange-200">
              About
            </Link>
            <Link href="/contact" className="hover:text-orange-200">
              Contact
            </Link>
          </div>
          <button className="bg-white text-orange-600 px-4 py-2 rounded hover:bg-gray-100">
            Login
          </button>
        </div>
      </div>
    </nav>
  );
};

export default Navigation;