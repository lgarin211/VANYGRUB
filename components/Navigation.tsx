import React from 'react';
import Link from 'next/link';

const Navigation: React.FC = () => {
  return (
    <nav className="text-white bg-orange-600">
      <div className="container px-4 mx-auto">
        <div className="flex items-center justify-between py-4">
          <Link href="/" className="text-2xl font-bold">
            VANY GROUP
          </Link>
          <div className="hidden space-x-6 md:flex">
            <Link href="/vny" className="hover:text-orange-200">
              VNY Store
            </Link>
            <Link href="/menu" className="hover:text-orange-200">
              Menu
            </Link>
            <Link href="/gallery" className="hover:text-orange-200">
              Gallery
            </Link>
            <Link href="/about" className="hover:text-orange-200">
              About
            </Link>
            <Link href="/contact" className="hover:text-orange-200">
              Contact
            </Link>
          </div>
          <button className="px-4 py-2 text-orange-600 bg-white rounded hover:bg-gray-100">
            Login
          </button>
        </div>
      </div>
    </nav>
  );
};

export default Navigation;