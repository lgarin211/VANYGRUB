import React from 'react';
import Link from 'next/link';

const Footer: React.FC = () => {
  return (
    <footer className="bg-red-800 text-white py-12">
      <div className="container mx-auto px-4">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {/* Logo and Contact */}
          <div data-aos="fade-right" data-aos-delay="100">
            <div className="text-4xl font-bold tracking-widest mb-4">
              VNY
            </div>
            <div className="space-y-2">
              <p>+62 123 456 789</p>
              <p>VNY@gmail.com</p>
            </div>
            
            {/* Social Media */}
            <div className="flex space-x-4 mt-6">
              <Link 
                href="#" 
                className="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors"
              >
                <span className="text-sm">f</span>
              </Link>
              <Link 
                href="#" 
                className="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors"
              >
                <span className="text-sm">t</span>
              </Link>
            </div>
          </div>

          {/* Quick Action */}
          <div data-aos="fade-up" data-aos-delay="200">
            <h3 className="text-lg font-semibold mb-4">Quick Action</h3>
            <div className="space-y-2">
              <Link href="/vny/product" className="block hover:text-red-200 transition-colors">
                Product
              </Link>
              <Link href="/gallery" className="block hover:text-red-200 transition-colors">
                Gallery
              </Link>
            </div>
          </div>

          {/* Additional Links */}
          <div data-aos="fade-left" data-aos-delay="300">
            <div className="space-y-2">
              <Link href="/our-group" className="block hover:text-red-200 transition-colors">
                Our Group
              </Link>
              <Link href="/contact" className="block hover:text-red-200 transition-colors">
                Contact VNY
              </Link>
            </div>
          </div>
        </div>

        {/* Copyright */}
        <div 
          className="border-t border-white/20 mt-8 pt-6 text-center"
          data-aos="fade-up" 
          data-aos-delay="400"
        >
          <p className="text-sm">© 2025 VNY. All rights reserved</p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;