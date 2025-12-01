'use client';

import CustomerReviewCards from '@/components/CustomerReviewCards';
import Link from 'next/link';

export default function CustomerReviewPage() {
  return (
    <div className="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
      {/* Header */}
      <div className="bg-white shadow-sm">
        <div className="container px-4 py-8 mx-auto">
          <div className="text-center">
            <h1 className="mb-2 text-4xl font-bold text-red-600">Review Pelanggan</h1>
            <p className="mb-6 text-gray-600">
              Lihat apa kata customer kami tentang pengalaman mereka dengan VNY Store
            </p>
            <div className="flex justify-center gap-4">
              <Link 
                href="/customerreview"
                className="px-6 py-3 text-white transition-colors bg-red-600 rounded-lg hover:bg-red-700"
              >
                Review Unggulan
              </Link>
              <Link 
                href="/customerreview/all"
                className="px-6 py-3 text-white transition-colors bg-gray-600 rounded-lg hover:bg-gray-700"
              >
                Semua Review
              </Link>
            </div>
          </div>
        </div>
      </div>

      {/* Featured Reviews with Effects */}
      <div className="container px-4 py-12 mx-auto">
        <div className="mb-12 text-center">
          <h2 className="mb-4 text-3xl font-bold text-gray-800">Review Pelanggan Unggulan</h2>
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
        <div className="mt-16 text-center">
          <div className="p-8 text-white rounded-lg bg-gradient-to-r from-red-600 to-red-700">
            <h3 className="mb-4 text-2xl font-bold">Sudah Beli Sepatu dari VNY Store?</h3>
            <p className="mb-6">
              Bagikan pengalaman Anda dan dapatkan kesempatan untuk ditampilkan di halaman ini!
            </p>
            <Link 
              href="/"
              className="inline-block px-8 py-3 font-semibold text-red-600 transition-colors bg-white rounded-lg hover:bg-gray-100"
            >
              Belanja Sekarang
            </Link>
          </div>
        </div>

        {/* View All Button */}
        <div className="mt-12 text-center">
          <Link 
            href="/customerreview/all"
            className="inline-flex items-center px-8 py-4 font-semibold text-white transition-colors bg-gray-800 rounded-lg hover:bg-gray-900"
          >
            Lihat Semua Review
            <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </Link>
        </div>
      </div>
    </div>
  );
}