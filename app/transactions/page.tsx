'use client';

import { useState, useEffect } from 'react';
import Image from 'next/image';
import Link from 'next/link';
import { showWarning } from '../../utils/sweetAlert';
import Header from '../../components/Header';
import { useTransactions } from '../../hooks/useApi';

interface TransactionItem {
  id: number;
  name: string;
  image: string;
  quantity: number;
  price: string;
  originalPrice: number;
  color: string;
  size: number;
}

interface Transaction {
  id: string;
  date: string;
  status: 'pending' | 'processing' | 'shipped' | 'delivered' | 'cancelled';
  items: TransactionItem[];
  total: number;
  shippingAddress: string;
  paymentMethod: string;
  trackingNumber?: string;
  customerInfo?: {
    name: string;
    phone: string;
    email: string;
    address: string;
  };
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

const TransactionsPage: React.FC = () => {
  const [activeTab, setActiveTab] = useState<'history' | 'checkout'>('history');
  const [selectedTransaction, setSelectedTransaction] = useState<Transaction | null>(null);
  const [showCustomerForm, setShowCustomerForm] = useState(false);
  const [customerInfo, setCustomerInfo] = useState<CustomerInfo>({
    name: '',
    phone: '',
    email: '',
    address: '',
    city: '',
    postalCode: '',
    notes: ''
  });

  // Get session ID from localStorage or generate one
  const getSessionId = () => {
    if (typeof window !== 'undefined') {
      let sessionId = localStorage.getItem('user_session_id');
      if (!sessionId) {
        sessionId = `session_${Date.now()}_${Math.random().toString(36).substring(2)}`;
        localStorage.setItem('user_session_id', sessionId);
      }
      return sessionId;
    }
    return 'default_session';
  };

  const sessionId = getSessionId();
  const { transactions: apiTransactions, loading, error, refreshTransactions } = useTransactions(sessionId);
  
  // Demo transaction data as fallback
  const demoTransactions: Transaction[] = [
    {
      id: "ORD-2024-001",
      date: "2024-01-15",
      status: "delivered",
      items: [
        {
          id: 1,
          name: "Air Jordan 1 Retro",
          image: "/temp/nike-just-do-it(6).jpg",
          quantity: 1,
          price: "Rp. 5.000.000",
          originalPrice: 5000000,
          color: "Black/Red",
          size: 42
        }
      ],
      total: 5550000,
      shippingAddress: "Jl. Sudirman No. 123, Jakarta Pusat, 10110",
      paymentMethod: "Credit Card **** 1234",
      trackingNumber: "JNE123456789",
      customerInfo: {
        name: "John Doe",
        phone: "082111424592",
        email: "john@example.com",
        address: "Jl. Sudirman No. 123, Jakarta Pusat, 10110"
      }
    },
    {
      id: "ORD-2024-002", 
      date: "2024-01-10",
      status: "processing",
      items: [
        {
          id: 2,
          name: "Nike Dunk Low",
          image: "/temp/nike-just-do-it(7).jpg",
          quantity: 1,
          price: "Rp. 4.500.000",
          originalPrice: 4500000,
          color: "Panda (Black/White)",
          size: 43
        }
      ],
      total: 4950000,
      shippingAddress: "Jl. Thamrin No. 45, Jakarta Selatan, 12190",
      paymentMethod: "Bank Transfer",
      customerInfo: {
        name: "Jane Smith",
        phone: "081234567890", 
        email: "jane@example.com",
        address: "Jl. Thamrin No. 45, Jakarta Selatan, 12190"
      }
    }
  ];

  // Combine API transactions with demo data
  const transactions = [...apiTransactions, ...demoTransactions];

  // Demo cart items for checkout
  const [cartItems] = useState<TransactionItem[]>([
    {
      id: 1,
      name: "Air Jordan 1 Retro",
      image: "/temp/nike-just-do-it(6).jpg",
      quantity: 1,
      price: "Rp. 5.000.000",
      originalPrice: 5000000,
      color: "Black/Red",
      size: 42
    },
    {
      id: 2,
      name: "Nike Dunk Low",
      image: "/temp/nike-just-do-it(7).jpg",
      quantity: 1,
      price: "Rp. 4.500.000",
      originalPrice: 4500000,
      color: "Panda (Black/White)",
      size: 43
    }
  ]);

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'delivered':
        return 'bg-green-100 text-green-800 border-green-200';
      case 'shipped':
        return 'bg-blue-100 text-blue-800 border-blue-200';
      case 'processing':
        return 'bg-yellow-100 text-yellow-800 border-yellow-200';
      case 'pending':
        return 'bg-orange-100 text-orange-800 border-orange-200';
      case 'cancelled':
        return 'bg-red-100 text-red-800 border-red-200';
      default:
        return 'bg-gray-100 text-gray-800 border-gray-200';
    }
  };

  const getStatusText = (status: string) => {
    switch (status) {
      case 'delivered':
        return 'Terkirim';
      case 'shipped':
        return 'Dikirim';
      case 'processing':
        return 'Diproses';
      case 'pending':
        return 'Menunggu';
      case 'cancelled':
        return 'Dibatalkan';
      default:
        return status;
    }
  };

  const formatPrice = (price: number) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(price);
  };

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
      day: 'numeric',
      month: 'long',
      year: 'numeric'
    });
  };

  const handleCustomerInfoChange = (field: keyof CustomerInfo, value: string) => {
    setCustomerInfo(prev => ({
      ...prev,
      [field]: value
    }));
  };

  const generateWhatsAppMessage = () => {
    const subtotal = cartItems.reduce((sum, item) => sum + (item.originalPrice * item.quantity), 0);
    const tax = subtotal * 0.1;
    const shipping = 50000;
    const total = subtotal + tax + shipping;

    let message = `🛒 *PESANAN BARU VNY STORE*\n\n`;
    message += `👤 *Data Pembeli:*\n`;
    message += `Nama: ${customerInfo.name}\n`;
    message += `Phone: ${customerInfo.phone}\n`;
    message += `Email: ${customerInfo.email}\n`;
    message += `Alamat: ${customerInfo.address}\n`;
    message += `Kota: ${customerInfo.city}\n`;
    message += `Kode Pos: ${customerInfo.postalCode}\n`;
    if (customerInfo.notes) {
      message += `Catatan: ${customerInfo.notes}\n`;
    }
    message += `\n📦 *Detail Pesanan:*\n`;
    
    cartItems.forEach((item, index) => {
      message += `${index + 1}. ${item.name}\n`;
      message += `   Warna: ${item.color}\n`;
      message += `   Ukuran: ${item.size}\n`;
      message += `   Qty: ${item.quantity}x\n`;
      message += `   Harga: ${item.price}\n`;
      message += `   Subtotal: ${formatPrice(item.originalPrice * item.quantity)}\n\n`;
    });

    message += `💰 *Ringkasan Harga:*\n`;
    message += `Subtotal: ${formatPrice(subtotal)}\n`;
    message += `Pajak (10%): ${formatPrice(tax)}\n`;
    message += `Ongkir: ${formatPrice(shipping)}\n`;
    message += `*TOTAL: ${formatPrice(total)}*\n\n`;
    message += `📅 Tanggal Pesan: ${new Date().toLocaleDateString('id-ID', {
      day: 'numeric',
      month: 'long', 
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    })}\n\n`;
    message += `Mohon konfirmasi pesanan ini. Terima kasih! 🙏`;

    return encodeURIComponent(message);
  };

  const handleSendToWhatsApp = () => {
    if (!customerInfo.name || !customerInfo.phone || !customerInfo.address) {
      showWarning('Data Tidak Lengkap', 'Mohon lengkapi data pembeli terlebih dahulu!');
      return;
    }

    const whatsappNumber = '6282111424592';
    const message = generateWhatsAppMessage();
    const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${message}`;
    
    window.open(whatsappUrl, '_blank');
  };

  const isFormValid = () => {
    return customerInfo.name && customerInfo.phone && customerInfo.email && 
           customerInfo.address && customerInfo.city && customerInfo.postalCode;
  };

  return (
    <div className="min-h-screen bg-gray-50">
      <Header />

      <div className="container px-4 py-8 mx-auto">
        {/* Breadcrumb */}
        <div className="mb-8">
          <div className="flex items-center space-x-2 text-sm">
            <Link href="/" className="text-red-600 hover:text-red-700">Home</Link>
            <span className="text-gray-400">/</span>
            <span className="text-gray-600">Transaksi</span>
          </div>
          <h1 className="mt-4 text-3xl font-bold text-gray-900">Transaksi</h1>
          <p className="text-gray-600">Kelola riwayat transaksi dan checkout pesanan</p>
        </div>

        {/* Tab Navigation */}
        <div className="mb-8">
          <div className="flex space-x-8 border-b border-gray-200">
            <button
              onClick={() => {
                setActiveTab('history');
                setSelectedTransaction(null);
              }}
              className={`py-4 px-2 border-b-2 font-medium text-sm ${
                activeTab === 'history'
                  ? 'border-red-600 text-red-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              }`}
            >
              Riwayat Transaksi
            </button>
            <button
              onClick={() => {
                setActiveTab('checkout');
                setSelectedTransaction(null);
              }}
              className={`py-4 px-2 border-b-2 font-medium text-sm ${
                activeTab === 'checkout'
                  ? 'border-red-600 text-red-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              }`}
            >
              Checkout ({cartItems.length})
            </button>
          </div>
        </div>

        {/* Transaction History Tab */}
        {activeTab === 'history' && !selectedTransaction && (
          <div>
            {loading && (
              <div className="flex items-center justify-center py-12">
                <div className="w-8 h-8 border-b-2 border-red-600 rounded-full animate-spin"></div>
                <span className="ml-3 text-gray-600">Memuat transaksi...</span>
              </div>
            )}

            {error && !loading && (
              <div className="py-12 text-center">
                <div className="inline-block p-6 border border-red-200 rounded-lg bg-red-50">
                  <p className="font-medium text-red-600">Gagal memuat data transaksi</p>
                  <p className="mt-1 text-sm text-red-500">{error}</p>
                  <button 
                    onClick={() => refreshTransactions()}
                    className="px-4 py-2 mt-3 text-white transition-colors bg-red-600 rounded-lg hover:bg-red-700"
                  >
                    Coba Lagi
                  </button>
                </div>
              </div>
            )}

            <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
              {!loading && transactions.length > 0 ? (
                transactions.map((transaction) => (
                  <div 
                    key={transaction.id}
                    onClick={() => setSelectedTransaction(transaction)}
                    className="transition-shadow bg-white border border-gray-200 shadow-sm cursor-pointer rounded-xl hover:shadow-md"
                  >
                    <div className="p-6">
                      <div className="flex items-start justify-between mb-4">
                        <div>
                          <h3 className="mb-1 font-semibold text-gray-900">#{transaction.id}</h3>
                          <p className="text-sm text-gray-500">{formatDate(transaction.date)}</p>
                        </div>
                        <span className={`px-3 py-1 rounded-full text-xs font-medium ${getStatusColor(transaction.status)}`}>
                          {getStatusText(transaction.status)}
                        </span>
                      </div>
                      
                      <div className="flex items-center mb-4 space-x-3">
                        {transaction.items.slice(0, 4).map((item: TransactionItem, index: number) => (
                          <div key={index} className="w-10 h-10 overflow-hidden bg-gray-100 rounded-lg">
                            <Image
                              src={item.image}
                              alt={item.name}
                              width={40}
                              height={40}
                              className="object-cover w-full h-full"
                            />
                          </div>
                        ))}
                        {transaction.items.length > 4 && (
                          <div className="flex items-center justify-center w-10 h-10 bg-gray-200 rounded-lg">
                            <span className="text-sm text-gray-600">+{transaction.items.length - 4}</span>
                          </div>
                        )}
                      </div>
                      
                      <div className="flex items-center justify-between">
                        <p className="text-sm text-gray-600">
                          {transaction.items.length} item • {transaction.customerInfo?.name}
                        </p>
                        <p className="text-xl font-bold text-red-600">
                          {formatPrice(transaction.total)}
                        </p>
                      </div>
                    </div>
                  </div>
                ))
              ) : null}
            </div>
            
            {/* API Transactions Summary */}
            {!loading && apiTransactions.length > 0 && (
              <div className="p-4 mt-6 border border-blue-200 rounded-lg bg-blue-50">
                <div className="flex items-center justify-between">
                  <div>
                    <h4 className="font-semibold text-blue-900">Data dari Server</h4>
                    <p className="text-sm text-blue-700">{apiTransactions.length} transaksi ditemukan dari database</p>
                  </div>
                  <button
                    onClick={() => refreshTransactions()}
                    className="px-4 py-2 text-sm text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700"
                  >
                    <svg className="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh
                  </button>
                </div>
              </div>
            )}
          </div>
        )}

        {/* Transaction Detail Modal */}
        {selectedTransaction && (
          <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
            <div className="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
              <div className="sticky top-0 px-6 py-4 bg-white border-b border-gray-200 rounded-t-2xl">
                <div className="flex items-center justify-between">
                  <button
                    onClick={() => setSelectedTransaction(null)}
                    className="flex items-center space-x-2 font-medium text-red-600 hover:text-red-700"
                  >
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                    </svg>
                    <span>Kembali</span>
                  </button>
                  <span className={`px-4 py-2 rounded-full text-sm font-semibold border ${getStatusColor(selectedTransaction.status)}`}>
                    {getStatusText(selectedTransaction.status)}
                  </span>
                </div>
              </div>

              <div className="p-6 space-y-8">
                {/* Order Info */}
                <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                  <div>
                    <h3 className="mb-4 text-lg font-semibold text-gray-900">Informasi Pesanan</h3>
                    <div className="p-4 space-y-3 rounded-lg bg-gray-50">
                      <div className="flex justify-between">
                        <span className="text-gray-600">Order ID:</span>
                        <span className="font-medium">{selectedTransaction.id}</span>
                      </div>
                      <div className="flex justify-between">
                        <span className="text-gray-600">Tanggal:</span>
                        <span className="font-medium">{formatDate(selectedTransaction.date)}</span>
                      </div>
                      <div className="flex justify-between">
                        <span className="text-gray-600">Total:</span>
                        <span className="font-bold text-red-600">{formatPrice(selectedTransaction.total)}</span>
                      </div>
                      {selectedTransaction.trackingNumber && (
                        <div className="flex justify-between">
                          <span className="text-gray-600">No. Resi:</span>
                          <span className="px-2 py-1 font-mono text-sm bg-yellow-100 rounded">
                            {selectedTransaction.trackingNumber}
                          </span>
                        </div>
                      )}
                    </div>
                  </div>

                  <div>
                    <h3 className="mb-4 text-lg font-semibold text-gray-900">Informasi Pembeli</h3>
                    <div className="p-4 space-y-3 rounded-lg bg-gray-50">
                      <div className="flex justify-between">
                        <span className="text-gray-600">Nama:</span>
                        <span className="font-medium">{selectedTransaction.customerInfo?.name}</span>
                      </div>
                      <div className="flex justify-between">
                        <span className="text-gray-600">Phone:</span>
                        <span className="font-medium">{selectedTransaction.customerInfo?.phone}</span>
                      </div>
                      <div className="flex justify-between">
                        <span className="text-gray-600">Email:</span>
                        <span className="font-medium">{selectedTransaction.customerInfo?.email}</span>
                      </div>
                    </div>
                  </div>
                </div>

                {/* Items */}
                <div>
                  <h3 className="mb-4 text-lg font-semibold text-gray-900">Produk yang Dibeli</h3>
                  <div className="space-y-4">
                    {selectedTransaction.items.map((item: TransactionItem, index: number) => (
                      <div key={index} className="flex items-center p-4 space-x-4 rounded-lg bg-gray-50">
                        <div className="w-16 h-16 overflow-hidden bg-white rounded-lg">
                          <Image
                            src={item.image}
                            alt={item.name}
                            width={64}
                            height={64}
                            className="object-cover w-full h-full"
                          />
                        </div>
                        <div className="flex-1">
                          <h4 className="font-medium text-gray-900">{item.name}</h4>
                          <div className="flex items-center space-x-4 text-sm text-gray-600">
                            <span>Warna: {item.color}</span>
                            <span>•</span>
                            <span>Ukuran: {item.size}</span>
                            <span>•</span>
                            <span>Qty: {item.quantity}</span>
                          </div>
                        </div>
                        <div className="text-right">
                          <p className="font-medium text-gray-900">{item.price}</p>
                          <p className="text-sm text-gray-500">
                            {formatPrice(item.originalPrice * item.quantity)}
                          </p>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>

                {/* Shipping Address */}
                <div>
                  <h3 className="mb-4 text-lg font-semibold text-gray-900">Alamat Pengiriman</h3>
                  <div className="p-4 rounded-lg bg-gray-50">
                    <p className="text-gray-800">{selectedTransaction.shippingAddress}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        )}

        {/* Checkout Tab */}
        {activeTab === 'checkout' && (
          <div className="grid grid-cols-1 gap-8 lg:grid-cols-3">
            {/* Customer Form */}
            <div className="lg:col-span-2">
              <div className="overflow-hidden bg-white shadow-sm rounded-2xl">
                <div className="px-6 py-4 border-b border-gray-200 bg-gray-50">
                  <h2 className="text-xl font-semibold text-gray-900">Data Pembeli</h2>
                  <p className="text-sm text-gray-600">Lengkapi informasi untuk mengirim pesanan via WhatsApp</p>
                </div>

                <div className="p-6 space-y-6">
                  <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                      <label className="block mb-2 text-sm font-medium text-gray-700">
                        Nama Lengkap <span className="text-red-500">*</span>
                      </label>
                      <input
                        type="text"
                        value={customerInfo.name}
                        onChange={(e) => handleCustomerInfoChange('name', e.target.value)}
                        className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="Masukkan nama lengkap"
                      />
                    </div>

                    <div>
                      <label className="block mb-2 text-sm font-medium text-gray-700">
                        Nomor WhatsApp <span className="text-red-500">*</span>
                      </label>
                      <input
                        type="tel"
                        value={customerInfo.phone}
                        onChange={(e) => handleCustomerInfoChange('phone', e.target.value)}
                        className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="08xxxxxxxxxx"
                      />
                    </div>
                  </div>

                  <div>
                    <label className="block mb-2 text-sm font-medium text-gray-700">
                      Email <span className="text-red-500">*</span>
                    </label>
                    <input
                      type="email"
                      value={customerInfo.email}
                      onChange={(e) => handleCustomerInfoChange('email', e.target.value)}
                      className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                      placeholder="nama@email.com"
                    />
                  </div>

                  <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div className="md:col-span-2">
                      <label className="block mb-2 text-sm font-medium text-gray-700">
                        Alamat Lengkap <span className="text-red-500">*</span>
                      </label>
                      <textarea
                        value={customerInfo.address}
                        onChange={(e) => handleCustomerInfoChange('address', e.target.value)}
                        rows={3}
                        className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="Jl. Contoh No. 123, RT/RW, Kelurahan, Kecamatan"
                      />
                    </div>

                    <div>
                      <label className="block mb-2 text-sm font-medium text-gray-700">
                        Kota <span className="text-red-500">*</span>
                      </label>
                      <input
                        type="text"
                        value={customerInfo.city}
                        onChange={(e) => handleCustomerInfoChange('city', e.target.value)}
                        className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="Jakarta"
                      />
                    </div>

                    <div>
                      <label className="block mb-2 text-sm font-medium text-gray-700">
                        Kode Pos <span className="text-red-500">*</span>
                      </label>
                      <input
                        type="text"
                        value={customerInfo.postalCode}
                        onChange={(e) => handleCustomerInfoChange('postalCode', e.target.value)}
                        className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="12345"
                      />
                    </div>
                  </div>

                  <div>
                    <label className="block mb-2 text-sm font-medium text-gray-700">
                      Catatan Tambahan <span className="text-red-500">*</span>
                    </label>
                    <textarea
                      value={customerInfo.notes}
                      onChange={(e) => handleCustomerInfoChange('notes', e.target.value)}
                      rows={3}
                      className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                      placeholder="Catatan khusus untuk pesanan (wajib diisi)"
                    />
                  </div>
                </div>
              </div>
            </div>

            {/* Order Summary */}
            <div className="space-y-6">
              <div className="overflow-hidden bg-white shadow-sm rounded-2xl">
                <div className="px-6 py-4 border-b border-gray-200 bg-gray-50">
                  <h2 className="text-xl font-semibold text-gray-900">Ringkasan Pesanan</h2>
                </div>

                <div className="p-6">
                  <div className="space-y-4">
                    {cartItems.map((item: TransactionItem, index: number) => (
                      <div key={index} className="flex items-center space-x-4">
                        <div className="w-16 h-16 overflow-hidden bg-gray-100 rounded-lg">
                          <Image
                            src={item.image}
                            alt={item.name}
                            width={64}
                            height={64}
                            className="object-cover w-full h-full"
                          />
                        </div>
                        <div className="flex-1">
                          <h4 className="text-sm font-medium text-gray-900">{item.name}</h4>
                          <div className="text-xs text-gray-600">
                            <span>{item.color} • Size {item.size} • Qty {item.quantity}</span>
                          </div>
                        </div>
                        <div className="text-sm font-medium text-gray-900">
                          {item.price}
                        </div>
                      </div>
                    ))}
                  </div>

                  <div className="pt-6 mt-6 space-y-4 border-t border-gray-200">
                    <div className="flex justify-between text-sm">
                      <span className="text-gray-600">Subtotal:</span>
                      <span className="font-medium">
                        {formatPrice(cartItems.reduce((sum, item) => sum + (item.originalPrice * item.quantity), 0))}
                      </span>
                    </div>
                    <div className="flex justify-between text-sm">
                      <span className="text-gray-600">Pajak (10%):</span>
                      <span className="font-medium">
                        {formatPrice(cartItems.reduce((sum, item) => sum + (item.originalPrice * item.quantity), 0) * 0.1)}
                      </span>
                    </div>
                    <div className="flex justify-between text-sm">
                      <span className="text-gray-600">Ongkos Kirim:</span>
                      <span className="font-medium">Rp 50.000</span>
                    </div>
                    <div className="pt-4 border-t border-gray-200">
                      <div className="flex justify-between">
                        <span className="text-base font-semibold text-gray-900">Total:</span>
                        <span className="text-lg font-bold text-red-600">
                          {formatPrice(
                            cartItems.reduce((sum, item) => sum + (item.originalPrice * item.quantity), 0) * 1.1 + 50000
                          )}
                        </span>
                      </div>
                    </div>
                  </div>

                  <button
                    onClick={handleSendToWhatsApp}
                    disabled={!isFormValid()}
                    className={`w-full mt-6 py-4 px-6 rounded-xl font-semibold transition-all ${
                      isFormValid()
                        ? 'bg-green-600 hover:bg-green-700 text-white shadow-lg hover:shadow-xl'
                        : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                    }`}
                  >
                    <div className="flex items-center justify-center space-x-2">
                      <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                      </svg>
                      <span>Kirim ke WhatsApp</span>
                    </div>
                  </button>
                </div>
              </div>
            </div>
          </div>
        )}
      </div>

      {/* Footer */}
      <footer className="py-16 text-white bg-black">
        <div className="container px-4 mx-auto">
          <div className="grid grid-cols-1 gap-8 md:grid-cols-4">
            <div className="md:col-span-2">
              <div className="mb-6">
                <h3 className="text-2xl font-bold text-red-600">VNY</h3>
                <p className="max-w-md mt-2 text-gray-300">
                  Premium sneakers collection untuk gaya hidup aktif dan stylish. 
                  Kualitas terbaik dengan desain terdepan.
                </p>
              </div>

              <div className="flex space-x-4">
                <div className="flex items-center justify-center w-10 h-10 transition-colors rounded-full bg-white/10 hover:bg-white/20">
                  <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                  </svg>
                </div>
              </div>
            </div>

            <div>
              <div className="space-y-2 text-sm text-white/80">
                <div>Our Group</div>
                <div>Contact VNY</div>
              </div>
            </div>
          </div>

          <div className="pt-8 mt-8 text-sm text-center border-t border-white/20 text-white/60">
            © 2025 VNY. All rights reserved
          </div>
        </div>
      </footer>
    </div>
  );
};

export default TransactionsPage;