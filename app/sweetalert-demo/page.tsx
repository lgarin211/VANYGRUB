'use client';

import React from 'react';
import { showSuccess, showError, showWarning, showInfo, showConfirm, showCart, showOrderSuccess } from '../../utils/sweetAlert';

const SweetAlertDemo: React.FC = () => {
  const handleShowSuccess = () => {
    showSuccess('Berhasil!', 'Operasi telah berhasil dilakukan');
  };

  const handleShowError = () => {
    showError('Terjadi Error!', 'Mohon coba lagi beberapa saat');
  };

  const handleShowWarning = () => {
    showWarning('Peringatan!', 'Mohon lengkapi data yang diperlukan');
  };

  const handleShowInfo = () => {
    showInfo('Informasi', 'Fitur ini akan segera tersedia');
  };

  const handleShowConfirm = async () => {
    const result = await showConfirm('Hapus Item?', 'Item yang dihapus tidak dapat dikembalikan');
    if (result.isConfirmed) {
      showSuccess('Terhapus!', 'Item telah berhasil dihapus');
    }
  };

  const handleShowCart = async () => {
    const result = await showCart('Berhasil Ditambahkan!', '1 Nike Air Max telah ditambahkan ke keranjang');
    if (result.isConfirmed) {
      showInfo('Redirect', 'Menuju ke halaman keranjang...');
    }
  };

  const handleShowOrderSuccess = () => {
    // +62 813-1587-1101
    showOrderSuccess('VNY-2024-001', 'https://wa.me/6281315871101');
  };

  return (
    <div className="min-h-screen py-12 bg-gray-100">
      <div className="container px-4 mx-auto">
        <div className="max-w-4xl mx-auto">
          <div className="p-8 bg-white shadow-lg rounded-2xl">
            <h1 className="mb-8 text-3xl font-bold text-center text-gray-900">
              SweetAlert2 Demo
            </h1>
            <p className="mb-8 text-center text-gray-600">
              Semua alert() telah diganti dengan SweetAlert2 yang lebih modern dan user-friendly
            </p>

            <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
              {/* Success Alert */}
              <div className="p-6 border border-green-200 bg-green-50 rounded-xl">
                <h3 className="mb-3 font-semibold text-green-800">Success Alert</h3>
                <p className="mb-4 text-sm text-green-600">
                  Digunakan untuk notifikasi berhasil
                </p>
                <button
                  onClick={handleShowSuccess}
                  className="w-full px-4 py-2 text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700"
                >
                  Show Success
                </button>
              </div>

              {/* Error Alert */}
              <div className="p-6 border border-red-200 bg-red-50 rounded-xl">
                <h3 className="mb-3 font-semibold text-red-800">Error Alert</h3>
                <p className="mb-4 text-sm text-red-600">
                  Digunakan untuk notifikasi error
                </p>
                <button
                  onClick={handleShowError}
                  className="w-full px-4 py-2 text-white transition-colors bg-red-600 rounded-lg hover:bg-red-700"
                >
                  Show Error
                </button>
              </div>

              {/* Warning Alert */}
              <div className="p-6 border border-yellow-200 bg-yellow-50 rounded-xl">
                <h3 className="mb-3 font-semibold text-yellow-800">Warning Alert</h3>
                <p className="mb-4 text-sm text-yellow-600">
                  Digunakan untuk peringatan
                </p>
                <button
                  onClick={handleShowWarning}
                  className="w-full px-4 py-2 text-white transition-colors bg-yellow-600 rounded-lg hover:bg-yellow-700"
                >
                  Show Warning
                </button>
              </div>

              {/* Info Alert */}
              <div className="p-6 border border-blue-200 bg-blue-50 rounded-xl">
                <h3 className="mb-3 font-semibold text-blue-800">Info Alert</h3>
                <p className="mb-4 text-sm text-blue-600">
                  Digunakan untuk informasi
                </p>
                <button
                  onClick={handleShowInfo}
                  className="w-full px-4 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700"
                >
                  Show Info
                </button>
              </div>

              {/* Confirm Alert */}
              <div className="p-6 border border-gray-200 bg-gray-50 rounded-xl">
                <h3 className="mb-3 font-semibold text-gray-800">Confirm Alert</h3>
                <p className="mb-4 text-sm text-gray-600">
                  Digunakan untuk konfirmasi
                </p>
                <button
                  onClick={handleShowConfirm}
                  className="w-full px-4 py-2 text-white transition-colors bg-gray-600 rounded-lg hover:bg-gray-700"
                >
                  Show Confirm
                </button>
              </div>

              {/* Cart Alert */}
              <div className="p-6 border border-purple-200 bg-purple-50 rounded-xl">
                <h3 className="mb-3 font-semibold text-purple-800">Cart Alert</h3>
                <p className="mb-4 text-sm text-purple-600">
                  Digunakan untuk add to cart
                </p>
                <button
                  onClick={handleShowCart}
                  className="w-full px-4 py-2 text-white transition-colors bg-purple-600 rounded-lg hover:bg-purple-700"
                >
                  Show Cart
                </button>
              </div>
            </div>

            {/* Order Success - Full Width */}
            <div className="p-6 mt-8 border border-green-200 bg-gradient-to-r from-green-50 to-blue-50 rounded-xl">
              <h3 className="mb-3 font-semibold text-green-800">Order Success Alert</h3>
              <p className="mb-4 text-sm text-green-600">
                Digunakan untuk konfirmasi pesanan berhasil dengan kode pesanan dan link WhatsApp
              </p>
              <button
                onClick={handleShowOrderSuccess}
                className="px-6 py-3 text-white transition-all rounded-lg bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700"
              >
                Show Order Success
              </button>
            </div>

            {/* Implementation Note */}
            <div className="p-6 mt-8 bg-gray-50 rounded-xl">
              <h3 className="mb-3 font-semibold text-gray-800">📋 Implementation Details</h3>
              <div className="space-y-2 text-sm text-gray-600">
                <p>✅ <strong>Transactions page:</strong> Form validation alert</p>
                <p>✅ <strong>Cart page:</strong> Promo code alerts, form validation, order success</p>
                <p>✅ <strong>Product detail page:</strong> Add to cart success, error handling</p>
                <p>✅ <strong>Custom styling:</strong> Matching brand colors and responsive design</p>
                <p>✅ <strong>Mobile optimized:</strong> Touch-friendly and responsive alerts</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default SweetAlertDemo;