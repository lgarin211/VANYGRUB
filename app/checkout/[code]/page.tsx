'use client';

import React, { useState, useEffect } from 'react';
import Image from 'next/image';
import Link from 'next/link';
import { useParams } from 'next/navigation';
import Header from '../../../components/Header';
import { useOrderTracking } from '../../../hooks/useApi';

interface CartItem {
  id: number;
  name: string;
  price: string;
  originalPrice: number;
  image: string;
  color: string;
  size: number;
  quantity: number;
  discount?: string;
}

interface CustomerInfo {
  name: string;
  phone: string;
  email: string;
  address: string;
  city: string;
  postalCode: string;
  notes: string;
}

interface OrderData {
  orderCode: string;
  customerInfo: CustomerInfo;
  items: CartItem[];
  pricing: {
    subtotal: number;
    itemDiscount: number;
    promoDiscount: number;
    appliedPromo: string | null;
    tax: number;
    shipping: number;
    total: number;
  };
  orderDate: string;
  status: 'pending' | 'confirmed' | 'processing' | 'shipped' | 'delivered' | 'cancelled';
}

const CheckoutTrackingPage: React.FC = () => {
  const params = useParams();
  const orderCode = params.code as string;
  const { orderData, loading, error, refetch } = useOrderTracking(orderCode);

  const formatPrice = (price: number) => {
    return `Rp ${price.toLocaleString('id-ID')}`;
  };

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
      day: 'numeric',
      month: 'long',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  };

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'pending': return 'bg-yellow-100 text-yellow-800 border-yellow-200';
      case 'confirmed': return 'bg-blue-100 text-blue-800 border-blue-200';
      case 'processing': return 'bg-purple-100 text-purple-800 border-purple-200';
      case 'shipped': return 'bg-indigo-100 text-indigo-800 border-indigo-200';
      case 'delivered': return 'bg-green-100 text-green-800 border-green-200';
      case 'cancelled': return 'bg-red-100 text-red-800 border-red-200';
      default: return 'bg-gray-100 text-gray-800 border-gray-200';
    }
  };

  const getStatusText = (status: string) => {
    switch (status) {
      case 'pending': return 'Menunggu Konfirmasi';
      case 'confirmed': return 'Dikonfirmasi';
      case 'processing': return 'Sedang Diproses';
      case 'shipped': return 'Sedang Dikirim';
      case 'delivered': return 'Selesai';
      case 'cancelled': return 'Dibatalkan';
      default: return 'Status Tidak Diketahui';
    }
  };

  const getStatusIcon = (status: string) => {
    switch (status) {
      case 'pending': 
        return (
          <svg className="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        );
      case 'confirmed':
        return (
          <svg className="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        );
      case 'processing':
        return (
          <svg className="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        );
      case 'shipped':
        return (
          <svg className="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
          </svg>
        );
      case 'delivered':
        return (
          <svg className="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
          </svg>
        );
      case 'cancelled':
        return (
          <svg className="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
          </svg>
        );
      default:
        return (
          <svg className="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        );
    }
  };

  const handlePrint = () => {
    window.print();
  };

  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50">
        <Header />
        <div className="flex items-center justify-center min-h-[70vh]">
          <div className="max-w-md p-8 mx-auto text-center">
            <div className="w-16 h-16 mx-auto mb-6 border-b-4 border-red-600 rounded-full animate-spin"></div>
            <h2 className="mb-2 text-xl font-semibold text-gray-900">Memuat Data Pesanan</h2>
            <p className="text-gray-600">Sedang mengambil data pesanan dari server...</p>
          </div>
        </div>
      </div>
    );
  }

  if (!orderData || error) {
    return (
      <div className="min-h-screen bg-gray-50">
        <Header />
        <div className="flex items-center justify-center min-h-[70vh]">
          <div className="max-w-lg p-8 mx-auto text-center bg-white shadow-lg rounded-2xl">
            <div className="flex items-center justify-center w-24 h-24 mx-auto mb-6 bg-red-100 rounded-full">
              <svg className="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
              </svg>
            </div>
            <h1 className="mb-4 text-3xl font-bold text-gray-900">Pesanan Tidak Ditemukan</h1>
            <p className="mb-2 text-lg text-gray-600">
              Pesanan dengan kode:
            </p>
            <div className="p-4 mb-6 bg-gray-100 rounded-lg">
              <code className="font-mono text-xl font-bold text-red-600">{orderCode}</code>
            </div>
            <p className="mb-8 text-gray-600">
              Tidak ditemukan di sistem. Pesanan mungkin sudah kedaluwarsa atau belum tersinkronisasi.
            </p>
            <div className="flex flex-col justify-center gap-4 sm:flex-row">
              <button
                onClick={() => refetch()}
                className="inline-flex items-center px-6 py-3 font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700"
              >
                <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Coba Lagi
              </button>
              <Link
                href="/vny/cart"
                className="inline-flex items-center px-6 py-3 font-medium text-white transition-colors bg-red-600 rounded-lg hover:bg-red-700"
              >
                <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Keranjang
              </Link>
            </div>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="print:hidden">
        <Header />
      </div>

      <div className="container max-w-4xl px-4 py-8 mx-auto">
        {/* Breadcrumb */}
        <div className="mb-8 print:hidden">
          <div className="flex items-center space-x-2 text-sm">
            <Link href="/" className="text-red-600 hover:text-red-700">Home</Link>
            <span className="text-gray-400">/</span>
            <Link href="/vny/cart" className="text-red-600 hover:text-red-700">Cart</Link>
            <span className="text-gray-400">/</span>
            <span className="text-gray-600">Checkout</span>
            <span className="text-gray-400">/</span>
            <span className="text-gray-600">{orderCode}</span>
          </div>
        </div>

        {/* Order Receipt */}
        <div className="overflow-hidden bg-white shadow-sm rounded-2xl">
          {/* Header */}
          <div className="px-8 py-6 text-white bg-gradient-to-r from-red-800 to-red-700">
            <div className="flex items-center justify-between">
              <div>
                <h1 className="mb-2 text-2xl font-bold">Receipt Pesanan</h1>
                <p className="text-red-100">VNY Store - Premium Sneakers</p>
              </div>
              <div className="text-right">
                <div className="text-3xl font-bold">VNY</div>
                <div className="text-sm text-red-100">Est. 2024</div>
              </div>
            </div>
          </div>

          {/* Order Info */}
          <div className="px-8 py-6 border-b border-gray-200">
            <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
              <div>
                <h2 className="mb-4 text-lg font-semibold text-gray-900">Informasi Pesanan</h2>
                <div className="space-y-4">
                  <div className="flex flex-col gap-1 sm:flex-row sm:justify-between sm:gap-0">
                    <span className="font-medium text-gray-700">Kode Pesanan:</span>
                    <div className="px-4 py-2 font-mono text-sm font-bold text-red-800 bg-red-100 border border-red-200 rounded-lg">
                      {orderData.orderCode}
                    </div>
                  </div>
                  <div className="flex flex-col gap-1 sm:flex-row sm:justify-between sm:gap-0">
                    <span className="font-medium text-gray-700">Tanggal Pesan:</span>
                    <span className="font-bold text-gray-900">{formatDate(orderData.orderDate)}</span>
                  </div>
                  <div className="flex flex-col gap-2 sm:flex-row sm:justify-between sm:gap-0">
                    <span className="font-medium text-gray-700">Status:</span>
                    <div className={`inline-flex items-center space-x-2 px-4 py-2 rounded-full text-sm font-bold border-2 ${getStatusColor(orderData.status)}`}>
                      {getStatusIcon(orderData.status)}
                      <span>{getStatusText(orderData.status)}</span>
                    </div>
                  </div>
                </div>
              </div>

              <div>
                <h2 className="mb-4 text-lg font-semibold text-gray-900">Data Pembeli</h2>
                <div className="space-y-3">
                  <div className="flex flex-col sm:flex-row sm:justify-between">
                    <span className="font-medium text-gray-700">Nama:</span> 
                    <span className="font-semibold text-gray-900">{orderData.customerInfo.name}</span>
                  </div>
                  <div className="flex flex-col sm:flex-row sm:justify-between">
                    <span className="font-medium text-gray-700">Phone:</span> 
                    <span className="font-semibold text-gray-900">{orderData.customerInfo.phone}</span>
                  </div>
                  <div className="flex flex-col sm:flex-row sm:justify-between">
                    <span className="font-medium text-gray-700">Email:</span> 
                    <span className="font-semibold text-gray-900 break-all">{orderData.customerInfo.email}</span>
                  </div>
                  <div className="flex flex-col sm:flex-row sm:justify-between">
                    <span className="font-medium text-gray-700">Alamat:</span> 
                    <span className="font-semibold text-right text-gray-900 break-words sm:max-w-xs">{orderData.customerInfo.address}</span>
                  </div>
                  <div className="flex flex-col sm:flex-row sm:justify-between">
                    <span className="font-medium text-gray-700">Kota:</span> 
                    <span className="font-semibold text-gray-900">{orderData.customerInfo.city}</span>
                  </div>
                  <div className="flex flex-col sm:flex-row sm:justify-between">
                    <span className="font-medium text-gray-700">Kode Pos:</span> 
                    <span className="font-semibold text-gray-900">{orderData.customerInfo.postalCode}</span>
                  </div>
                  {orderData.customerInfo.notes && (
                    <div className="p-3 mt-4 border-l-4 border-blue-400 rounded-lg bg-blue-50">
                      <span className="font-medium text-gray-700">Catatan:</span>
                      <p className="mt-1 font-semibold text-gray-900">{orderData.customerInfo.notes}</p>
                    </div>
                  )}
                </div>
              </div>
            </div>
          </div>

          {/* Order Items */}
          <div className="px-8 py-6">
            <h2 className="mb-6 text-lg font-semibold text-gray-900">Detail Produk</h2>
            <div className="space-y-4">
              {orderData.items.map((item: CartItem, index: number) => (
                <div key={index} className="flex flex-col items-start p-4 space-y-3 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
                  <div className="w-20 h-20 overflow-hidden bg-gray-100 rounded-lg shadow-sm">
                    <Image
                      src={item.image}
                      alt={item.name}
                      width={80}
                      height={80}
                      className="object-cover w-full h-full"
                    />
                  </div>
                  <div className="flex-1 min-w-0">
                    <h3 className="mb-2 text-lg font-bold text-gray-900">{item.name}</h3>
                    <div className="grid grid-cols-2 gap-2 text-sm">
                      <div>
                        <span className="font-medium text-gray-600">Warna:</span>
                        <span className="ml-1 font-semibold text-gray-900">{item.color}</span>
                      </div>
                      <div>
                        <span className="font-medium text-gray-600">Ukuran:</span>
                        <span className="ml-1 font-semibold text-gray-900">{item.size}</span>
                      </div>
                    </div>
                  </div>
                  <div className="flex flex-row w-full gap-4 sm:flex-col sm:gap-0 sm:w-auto">
                    <div className="flex-1 text-center sm:flex-none sm:mb-2">
                      <div className="text-xs font-medium text-gray-600">Quantity</div>
                      <div className="text-lg font-bold text-gray-900">{item.quantity}x</div>
                    </div>
                    <div className="flex-1 text-center sm:flex-none sm:mb-2">
                      <div className="text-xs font-medium text-gray-600">Harga Satuan</div>
                      <div className="font-bold text-red-600">{item.price}</div>
                    </div>
                    <div className="flex-1 text-center sm:flex-none">
                      <div className="text-xs font-medium text-gray-600">Subtotal</div>
                      <div className="text-lg font-bold text-gray-900">
                        {formatPrice(item.originalPrice * item.quantity)}
                      </div>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>

          {/* Price Summary */}
          <div className="px-8 py-6 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
            <div className="max-w-lg ml-auto">
              <h2 className="mb-6 text-xl font-bold text-gray-900">Ringkasan Pembayaran</h2>
              <div className="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <div className="space-y-4">
                  <div className="flex items-center justify-between py-2">
                    <span className="font-medium text-gray-700">Subtotal</span>
                    <span className="text-lg font-bold text-gray-900">{formatPrice(orderData.pricing.subtotal)}</span>
                  </div>
                  
                  {orderData.pricing.itemDiscount > 0 && (
                    <div className="flex items-center justify-between py-2 text-green-600">
                      <span className="font-medium">Diskon Produk</span>
                      <span className="font-bold">-{formatPrice(orderData.pricing.itemDiscount)}</span>
                    </div>
                  )}
                  
                  {orderData.pricing.promoDiscount > 0 && (
                    <div className="flex items-center justify-between py-2 text-green-600">
                      <span className="font-medium">Diskon Promo ({orderData.pricing.appliedPromo})</span>
                      <span className="font-bold">-{formatPrice(orderData.pricing.promoDiscount)}</span>
                    </div>
                  )}
                  
                  <div className="flex items-center justify-between py-2">
                    <span className="font-medium text-gray-700">Pajak (PPN 10%)</span>
                    <span className="font-bold text-gray-900">{formatPrice(orderData.pricing.tax)}</span>
                  </div>
                  
                  <div className="flex items-center justify-between py-2">
                    <span className="font-medium text-gray-700">Ongkos Kirim</span>
                    <span className="font-bold text-gray-900">{formatPrice(orderData.pricing.shipping)}</span>
                  </div>
                  
                  <div className="pt-4 mt-4 border-t border-gray-300">
                    <div className="flex items-center justify-between">
                      <span className="text-xl font-bold text-gray-900">Total Pembayaran</span>
                      <span className="text-2xl font-bold text-red-600">
                        {formatPrice(orderData.pricing.total)}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {/* Actions */}
          <div className="px-8 py-6 border-t border-gray-200 bg-gray-50 print:hidden">
            <div className="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
              <div className="p-4 border border-blue-200 rounded-lg bg-blue-50">
                <h3 className="mb-2 text-sm font-bold text-blue-900">Butuh Bantuan?</h3>
                <div className="space-y-1 text-sm">
                  <div className="flex items-center gap-2">
                    <svg className="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.570-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.106"/>
                    </svg>
                    <span className="font-bold text-gray-900">WhatsApp: +62 813-1587-1101</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <svg className="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                    <span className="font-bold text-gray-900">Email: vny@gmail.com</span>
                  </div>
                </div>
              </div>
              <div className="flex flex-col gap-3 sm:flex-row">
                <button
                  onClick={() => refetch()}
                  className="inline-flex items-center px-6 py-3 font-medium text-white transition-colors bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700"
                >
                  <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                  </svg>
                  Refresh Data
                </button>
                <button
                  onClick={handlePrint}
                  className="inline-flex items-center px-6 py-3 font-medium text-white transition-colors bg-gray-600 rounded-lg shadow-sm hover:bg-gray-700"
                >
                  <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                  </svg>
                  Print Receipt
                </button>
                <Link
                  href="/vny/cart"
                  className="inline-flex items-center px-6 py-3 font-medium text-white transition-colors bg-red-600 rounded-lg shadow-sm hover:bg-red-700"
                >
                  <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                  </svg>
                  Belanja Lagi
                </Link>
              </div>
            </div>
          </div>
        </div>

        {/* Status Timeline */}
        <div className="p-8 mt-8 bg-white shadow-sm rounded-2xl print:hidden">
          <h2 className="mb-6 text-lg font-semibold text-gray-900">Status Pelacakan Pesanan</h2>
          <div className="space-y-4">
            {[
              { status: 'pending', title: 'Pesanan Dibuat', desc: 'Pesanan Anda telah diterima dan menunggu konfirmasi' },
              { status: 'confirmed', title: 'Pesanan Dikonfirmasi', desc: 'Admin telah mengkonfirmasi pesanan Anda' },
              { status: 'processing', title: 'Sedang Diproses', desc: 'Pesanan Anda sedang disiapkan' },
              { status: 'shipped', title: 'Sedang Dikirim', desc: 'Pesanan dalam perjalanan ke alamat Anda' },
              { status: 'delivered', title: 'Pesanan Selesai', desc: 'Pesanan telah sampai di tujuan' }
            ].map((step: { status: string; title: string; desc: string }, index: number) => {
              const isActive = step.status === orderData.status;
              const isPassed = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'].indexOf(orderData.status) >= 
                              ['pending', 'confirmed', 'processing', 'shipped', 'delivered'].indexOf(step.status);
              
              return (
                <div key={step.status} className="flex items-center space-x-4">
                  <div className={`w-8 h-8 rounded-full flex items-center justify-center ${
                    isActive ? 'bg-red-600 text-white' : 
                    isPassed ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500'
                  }`}>
                    {isPassed && !isActive ? (
                      <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                      </svg>
                    ) : (
                      <span className="text-sm font-bold">{index + 1}</span>
                    )}
                  </div>
                  <div className="flex-1">
                    <h3 className={`font-semibold ${isActive ? 'text-red-600' : isPassed ? 'text-green-600' : 'text-gray-500'}`}>
                      {step.title}
                    </h3>
                    <p className="text-sm text-gray-600">{step.desc}</p>
                  </div>
                  {isActive && (
                    <div className="animate-pulse">
                      <div className="w-3 h-3 bg-red-600 rounded-full"></div>
                    </div>
                  )}
                </div>
              );
            })}
          </div>
        </div>
      </div>

      {/* Print Styles */}
      <style jsx global>{`
        @media print {
          body { margin: 0; }
          .print\\:hidden { display: none !important; }
          .container { max-width: none; margin: 0; padding: 0; }
          .bg-gray-50 { background: white !important; }
        }
      `}</style>
    </div>
  );
};

export default CheckoutTrackingPage;