'use client';

import React, { useState, useEffect } from 'react';
import Image from 'next/image';
import Link from 'next/link';
import { useParams } from 'next/navigation';
import Header from '../../../components/Header';

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
  const [orderData, setOrderData] = useState<OrderData | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (orderCode) {
      // Try to get order data from localStorage
      const storedData = localStorage.getItem(`order_${orderCode}`);
      
      if (storedData) {
        const data = JSON.parse(storedData) as OrderData;
        setOrderData(data);
      }
      
      setLoading(false);
    }
  }, [orderCode]);

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
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-red-600 mx-auto mb-4"></div>
          <p className="text-gray-600">Memuat data pesanan...</p>
        </div>
      </div>
    );
  }

  if (!orderData) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="text-center max-w-md mx-auto p-6">
          <div className="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg className="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
            </svg>
          </div>
          <h1 className="text-2xl font-bold text-gray-900 mb-4">Pesanan Tidak Ditemukan</h1>
          <p className="text-gray-600 mb-6">
            Maaf, pesanan dengan kode <strong>{orderCode}</strong> tidak ditemukan atau sudah kedaluwarsa.
          </p>
          <Link
            href="/cart"
            className="inline-flex items-center px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors"
          >
            <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Keranjang
          </Link>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="print:hidden">
        <Header />
      </div>

      <div className="container mx-auto px-4 py-8 max-w-4xl">
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
        <div className="bg-white rounded-2xl shadow-sm overflow-hidden">
          {/* Header */}
          <div className="px-8 py-6 bg-gradient-to-r from-red-800 to-red-700 text-white">
            <div className="flex items-center justify-between">
              <div>
                <h1 className="text-2xl font-bold mb-2">Receipt Pesanan</h1>
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
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <h2 className="text-lg font-semibold text-gray-900 mb-4">Informasi Pesanan</h2>
                <div className="space-y-3">
                  <div className="flex items-center justify-between">
                    <span className="text-gray-600">Kode Pesanan:</span>
                    <span className="font-mono text-sm bg-gray-100 px-3 py-1 rounded font-bold">
                      {orderData.orderCode}
                    </span>
                  </div>
                  <div className="flex items-center justify-between">
                    <span className="text-gray-600">Tanggal Pesan:</span>
                    <span className="font-medium">{formatDate(orderData.orderDate)}</span>
                  </div>
                  <div className="flex items-center justify-between">
                    <span className="text-gray-600">Status:</span>
                    <div className={`inline-flex items-center space-x-2 px-3 py-1 rounded-full text-sm font-medium border ${getStatusColor(orderData.status)}`}>
                      {getStatusIcon(orderData.status)}
                      <span>{getStatusText(orderData.status)}</span>
                    </div>
                  </div>
                </div>
              </div>

              <div>
                <h2 className="text-lg font-semibold text-gray-900 mb-4">Data Pembeli</h2>
                <div className="space-y-2 text-sm">
                  <div><strong>Nama:</strong> {orderData.customerInfo.name}</div>
                  <div><strong>Phone:</strong> {orderData.customerInfo.phone}</div>
                  <div><strong>Email:</strong> {orderData.customerInfo.email}</div>
                  <div><strong>Alamat:</strong> {orderData.customerInfo.address}</div>
                  <div><strong>Kota:</strong> {orderData.customerInfo.city}</div>
                  <div><strong>Kode Pos:</strong> {orderData.customerInfo.postalCode}</div>
                  {orderData.customerInfo.notes && (
                    <div><strong>Catatan:</strong> {orderData.customerInfo.notes}</div>
                  )}
                </div>
              </div>
            </div>
          </div>

          {/* Order Items */}
          <div className="px-8 py-6">
            <h2 className="text-lg font-semibold text-gray-900 mb-6">Detail Produk</h2>
            <div className="space-y-4">
              {orderData.items.map((item, index) => (
                <div key={index} className="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                  <div className="w-16 h-16 bg-white rounded-lg overflow-hidden">
                    <Image
                      src={item.image}
                      alt={item.name}
                      width={64}
                      height={64}
                      className="w-full h-full object-cover"
                    />
                  </div>
                  <div className="flex-1">
                    <h3 className="font-semibold text-gray-900">{item.name}</h3>
                    <div className="text-sm text-gray-600 space-y-1">
                      <p>Warna: {item.color}</p>
                      <p>Ukuran: {item.size}</p>
                    </div>
                  </div>
                  <div className="text-center">
                    <div className="text-sm text-gray-600">Qty</div>
                    <div className="font-semibold">{item.quantity}x</div>
                  </div>
                  <div className="text-right">
                    <div className="text-sm text-gray-600">Harga Satuan</div>
                    <div className="font-semibold text-red-600">{item.price}</div>
                  </div>
                  <div className="text-right">
                    <div className="text-sm text-gray-600">Subtotal</div>
                    <div className="font-bold text-gray-900">
                      {formatPrice(item.originalPrice * item.quantity)}
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>

          {/* Price Summary */}
          <div className="px-8 py-6 bg-gray-50 border-t border-gray-200">
            <div className="max-w-md ml-auto">
              <h2 className="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pembayaran</h2>
              <div className="space-y-3">
                <div className="flex justify-between">
                  <span className="text-gray-600">Subtotal</span>
                  <span className="font-medium">{formatPrice(orderData.pricing.subtotal)}</span>
                </div>
                
                {orderData.pricing.itemDiscount > 0 && (
                  <div className="flex justify-between text-green-600">
                    <span>Diskon Produk</span>
                    <span>-{formatPrice(orderData.pricing.itemDiscount)}</span>
                  </div>
                )}
                
                {orderData.pricing.promoDiscount > 0 && (
                  <div className="flex justify-between text-green-600">
                    <span>Diskon Promo ({orderData.pricing.appliedPromo})</span>
                    <span>-{formatPrice(orderData.pricing.promoDiscount)}</span>
                  </div>
                )}
                
                <div className="flex justify-between">
                  <span className="text-gray-600">Pajak (PPN 10%)</span>
                  <span className="font-medium">{formatPrice(orderData.pricing.tax)}</span>
                </div>
                
                <div className="flex justify-between">
                  <span className="text-gray-600">Ongkos Kirim</span>
                  <span className="font-medium">{formatPrice(orderData.pricing.shipping)}</span>
                </div>
                
                <div className="border-t border-gray-300 pt-3">
                  <div className="flex justify-between items-center">
                    <span className="text-xl font-bold text-gray-900">Total Pembayaran</span>
                    <span className="text-2xl font-bold text-red-600">
                      {formatPrice(orderData.pricing.total)}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {/* Actions */}
          <div className="px-8 py-6 border-t border-gray-200 print:hidden">
            <div className="flex items-center justify-between">
              <div className="text-sm text-gray-500">
                <p>Untuk bantuan atau pertanyaan, hubungi:</p>
                <p className="font-medium">WhatsApp: +62 821-1142-4592</p>
                <p className="font-medium">Email: vny@gmail.com</p>
              </div>
              <div className="space-x-4">
                <button
                  onClick={handlePrint}
                  className="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors"
                >
                  <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                  </svg>
                  Print Receipt
                </button>
                <Link
                  href="/vny/cart"
                  className="inline-flex items-center px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors"
                >
                  Belanja Lagi
                </Link>
              </div>
            </div>
          </div>
        </div>

        {/* Status Timeline */}
        <div className="mt-8 bg-white rounded-2xl shadow-sm p-8 print:hidden">
          <h2 className="text-lg font-semibold text-gray-900 mb-6">Status Pelacakan Pesanan</h2>
          <div className="space-y-4">
            {[
              { status: 'pending', title: 'Pesanan Dibuat', desc: 'Pesanan Anda telah diterima dan menunggu konfirmasi' },
              { status: 'confirmed', title: 'Pesanan Dikonfirmasi', desc: 'Admin telah mengkonfirmasi pesanan Anda' },
              { status: 'processing', title: 'Sedang Diproses', desc: 'Pesanan Anda sedang disiapkan' },
              { status: 'shipped', title: 'Sedang Dikirim', desc: 'Pesanan dalam perjalanan ke alamat Anda' },
              { status: 'delivered', title: 'Pesanan Selesai', desc: 'Pesanan telah sampai di tujuan' }
            ].map((step, index) => {
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