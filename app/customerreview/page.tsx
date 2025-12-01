'use client';

import CustomerReviewCards from '@/components/CustomerReviewCards';
import Link from 'next/link';

export default function CustomerReviewPage() {
  return (
    <div className="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
      {/* Header */}
      <div className="bg-white shadow-sm">
        <div className="container mx-auto px-4 py-8">
          <div className="text-center">
            <h1 className="text-4xl font-bold text-red-600 mb-2">Customer Reviews</h1>
            <p className="text-gray-600 mb-6">
              Lihat apa kata customer kami tentang pengalaman mereka dengan VNY Store
            </p>
            <div className="flex justify-center gap-4">
              <Link 
                href="/customerreview"
                className="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
              >
                Featured Reviews
              </Link>
              <Link 
                href="/customerreview/all"
                className="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
              >
                All Reviews
              </Link>
            </div>
          </div>
        </div>
      </div>

      {/* Featured Reviews with Effects */}
      <div className="container mx-auto px-4 py-12">
        <div className="text-center mb-12">
          <h2 className="text-3xl font-bold text-gray-800 mb-4">Featured Customer Reviews</h2>
          <p className="text-gray-600">
            Review terbaik dari customer kami yang puas dengan produk VNY Store
          </p>
        </div>

        {/* Cards with Effects */}
        <div className="customer-review-cards-container">
          <CustomerReviewCards 
            featured={true} 
            limit={6}
            className="featured-reviews"
          />
        </div>

        {/* Call to Action */}
        <div className="text-center mt-16">
          <div className="bg-gradient-to-r from-red-600 to-red-700 rounded-lg p-8 text-white">
            <h3 className="text-2xl font-bold mb-4">Sudah Beli Sepatu dari VNY Store?</h3>
            <p className="mb-6">
              Bagikan pengalaman Anda dan dapatkan kesempatan untuk ditampilkan di halaman ini!
            </p>
            <Link 
              href="/"
              className="inline-block bg-white text-red-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors"
            >
              Belanja Sekarang
            </Link>
          </div>
        </div>

        {/* View All Button */}
        <div className="text-center mt-12">
          <Link 
            href="/customerreview/all"
            className="inline-flex items-center px-8 py-4 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors font-semibold"
          >
            Lihat Semua Review
            <svg className="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </Link>
        </div>
      </div>
    </div>
  );
}