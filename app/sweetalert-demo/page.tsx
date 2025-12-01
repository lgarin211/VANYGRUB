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
    showOrderSuccess('VNY-2024-001', 'https://wa.me/6282111424592');
  };

  return (
    <div className="min-h-screen bg-gray-100 py-12">
      <div className="container mx-auto px-4">
        <div className="max-w-4xl mx-auto">
          <div className="bg-white rounded-2xl shadow-lg p-8">
            <h1 className="text-3xl font-bold text-center mb-8 text-gray-900">
              SweetAlert2 Demo
            </h1>
            <p className="text-center text-gray-600 mb-8">
              Semua alert() telah diganti dengan SweetAlert2 yang lebih modern dan user-friendly
            </p>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              {/* Success Alert */}
              <div className="bg-green-50 p-6 rounded-xl border border-green-200">
                <h3 className="font-semibold text-green-800 mb-3">Success Alert</h3>
                <p className="text-sm text-green-600 mb-4">
                  Digunakan untuk notifikasi berhasil
                </p>
                <button
                  onClick={handleShowSuccess}
                  className="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors"
                >
                  Show Success
                </button>
              </div>

              {/* Error Alert */}
              <div className="bg-red-50 p-6 rounded-xl border border-red-200">
                <h3 className="font-semibold text-red-800 mb-3">Error Alert</h3>
                <p className="text-sm text-red-600 mb-4">
                  Digunakan untuk notifikasi error
                </p>
                <button
                  onClick={handleShowError}
                  className="w-full bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors"
                >
                  Show Error
                </button>
              </div>

              {/* Warning Alert */}
              <div className="bg-yellow-50 p-6 rounded-xl border border-yellow-200">
                <h3 className="font-semibold text-yellow-800 mb-3">Warning Alert</h3>
                <p className="text-sm text-yellow-600 mb-4">
                  Digunakan untuk peringatan
                </p>
                <button
                  onClick={handleShowWarning}
                  className="w-full bg-yellow-600 text-white py-2 px-4 rounded-lg hover:bg-yellow-700 transition-colors"
                >
                  Show Warning
                </button>
              </div>

              {/* Info Alert */}
              <div className="bg-blue-50 p-6 rounded-xl border border-blue-200">
                <h3 className="font-semibold text-blue-800 mb-3">Info Alert</h3>
                <p className="text-sm text-blue-600 mb-4">
                  Digunakan untuk informasi
                </p>
                <button
                  onClick={handleShowInfo}
                  className="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors"
                >
                  Show Info
                </button>
              </div>

              {/* Confirm Alert */}
              <div className="bg-gray-50 p-6 rounded-xl border border-gray-200">
                <h3 className="font-semibold text-gray-800 mb-3">Confirm Alert</h3>
                <p className="text-sm text-gray-600 mb-4">
                  Digunakan untuk konfirmasi
                </p>
                <button
                  onClick={handleShowConfirm}
                  className="w-full bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors"
                >
                  Show Confirm
                </button>
              </div>

              {/* Cart Alert */}
              <div className="bg-purple-50 p-6 rounded-xl border border-purple-200">
                <h3 className="font-semibold text-purple-800 mb-3">Cart Alert</h3>
                <p className="text-sm text-purple-600 mb-4">
                  Digunakan untuk add to cart
                </p>
                <button
                  onClick={handleShowCart}
                  className="w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition-colors"
                >
                  Show Cart
                </button>
              </div>
            </div>

            {/* Order Success - Full Width */}
            <div className="mt-8 bg-gradient-to-r from-green-50 to-blue-50 p-6 rounded-xl border border-green-200">
              <h3 className="font-semibold text-green-800 mb-3">Order Success Alert</h3>
              <p className="text-sm text-green-600 mb-4">
                Digunakan untuk konfirmasi pesanan berhasil dengan kode pesanan dan link WhatsApp
              </p>
              <button
                onClick={handleShowOrderSuccess}
                className="bg-gradient-to-r from-green-600 to-blue-600 text-white py-3 px-6 rounded-lg hover:from-green-700 hover:to-blue-700 transition-all"
              >
                Show Order Success
              </button>
            </div>

            {/* Implementation Note */}
            <div className="mt-8 bg-gray-50 p-6 rounded-xl">
              <h3 className="font-semibold text-gray-800 mb-3">📋 Implementation Details</h3>
              <div className="text-sm text-gray-600 space-y-2">
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