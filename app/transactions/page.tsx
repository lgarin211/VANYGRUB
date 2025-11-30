'use client';

import React, { useState, useEffect } from 'react';
import Image from 'next/image';
import Link from 'next/link';

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

  // Demo transaction data
  const [transactions] = useState<Transaction[]>([
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
        phone: "081234567890",
        email: "john@example.com",
        address: "Jl. Sudirman No. 123, Jakarta Pusat"
      }
    },
    {
      id: "ORD-2024-002",
      date: "2024-02-10",
      status: "shipped",
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
        },
        {
          id: 3,
          name: "Converse Chuck 70",
          image: "/temp/nike-just-do-it(8).jpg",
          quantity: 1,
          price: "Rp. 3.800.000",
          originalPrice: 3800000,
          color: "Vintage Khaki/Brown",
          size: 41
        }
      ],
      total: 9130000,
      shippingAddress: "Jl. Thamrin No. 456, Jakarta Selatan, 12190",
      paymentMethod: "Bank Transfer BCA",
      trackingNumber: "TIKI987654321",
      customerInfo: {
        name: "Jane Smith",
        phone: "081987654321",
        email: "jane@example.com",
        address: "Jl. Thamrin No. 456, Jakarta Selatan"
      }
    },
    {
      id: "ORD-2024-003",
      date: "2024-11-28",
      status: "pending",
      items: [
        {
          id: 4,
          name: "Vans Old Skool",
          image: "/temp/nike-just-do-it(9).jpg",
          quantity: 2,
          price: "Rp. 3.200.000",
          originalPrice: 3200000,
          color: "Black/White",
          size: 42
        }
      ],
      total: 6900000,
      shippingAddress: "Jl. Gatot Subroto No. 789, Jakarta Barat, 11460",
      paymentMethod: "COD (Cash on Delivery)",
      customerInfo: {
        name: "Ahmad Rahman",
        phone: "081555666777",
        email: "ahmad@example.com",
        address: "Jl. Gatot Subroto No. 789, Jakarta Barat"
      }
    }
  ]);

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
      case 'pending': return 'bg-yellow-100 text-yellow-800 border-yellow-200';
      case 'processing': return 'bg-blue-100 text-blue-800 border-blue-200';
      case 'shipped': return 'bg-purple-100 text-purple-800 border-purple-200';
      case 'delivered': return 'bg-green-100 text-green-800 border-green-200';
      case 'cancelled': return 'bg-red-100 text-red-800 border-red-200';
      default: return 'bg-gray-100 text-gray-800 border-gray-200';
    }
  };

  const getStatusText = (status: string) => {
    switch (status) {
      case 'pending': return 'Menunggu Pembayaran';
      case 'processing': return 'Diproses';
      case 'shipped': return 'Dikirim';
      case 'delivered': return 'Selesai';
      case 'cancelled': return 'Dibatalkan';
      default: return 'Unknown';
    }
  };

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
      alert('Mohon lengkapi data pembeli terlebih dahulu!');
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
      {/* Header */}
      <header className="py-4 text-white bg-red-800">
        <div className="container flex items-center justify-between px-4 mx-auto">
          {/* Search Bar */}
          <div className="flex items-center">
            <div className="mr-8">
              <input
                type="text"
                placeholder="Q Search"
                className="px-2 py-1 text-white bg-transparent border-b border-white placeholder-white/70 focus:outline-none focus:border-white/100"
              />
            </div>
          </div>

          {/* Logo */}
          <Link href="/" className="text-2xl font-bold">
            VNY
          </Link>

          {/* Navigation */}
          <div className="flex space-x-4">
            <Link href="/vny/cart" className="relative flex items-center px-4 py-2 space-x-2 transition-all duration-300 rounded-lg hover:bg-white/10">
              <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13v6a2 2 0 002 2h10a2 2 0 002-2v-6m-10 0V9a2 2 0 012-2h6a2 2 0 012 2v4" />
              </svg>
              <span className="font-medium">CART</span>
            </Link>
            <Link href="/transactions" className="relative flex items-center px-4 py-2 space-x-2 text-yellow-300 transition-all duration-300 bg-white/10 rounded-lg">
              <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
              <span className="font-medium">TRANSACTION</span>
            </Link>
          </div>
        </div>

        {/* Navigation Menu */}
        <nav className="container px-4 mx-auto mt-4">
          <div className="flex space-x-8">
            <Link href="/" className="hover:text-gray-300">HOME</Link>
            <Link href="/vny/product" className="hover:text-gray-300">PRODUCT</Link>
            <Link href="/about" className="hover:text-gray-300">ABOUT VNY</Link>
            <Link href="/gallery" className="hover:text-gray-300">GALLERY</Link>
          </div>
        </nav>
      </header>

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

        {/* Tabs */}
        <div className="flex mb-8 bg-white rounded-xl shadow-sm overflow-hidden">
          <button
            onClick={() => setActiveTab('history')}
            className={`flex-1 px-6 py-4 font-semibold text-center transition-all duration-200 ${
              activeTab === 'history'
                ? 'bg-red-600 text-white'
                : 'bg-white text-gray-600 hover:bg-gray-50'
            }`}
          >
            <div className="flex items-center justify-center space-x-2">
              <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Riwayat Transaksi</span>
              <span className="px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded-full">
                {transactions.length}
              </span>
            </div>
          </button>
          <button
            onClick={() => setActiveTab('checkout')}
            className={`flex-1 px-6 py-4 font-semibold text-center transition-all duration-200 ${
              activeTab === 'checkout'
                ? 'bg-red-600 text-white'
                : 'bg-white text-gray-600 hover:bg-gray-50'
            }`}
          >
            <div className="flex items-center justify-center space-x-2">
              <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
              </svg>
              <span>Checkout WhatsApp</span>
              <span className="px-2 py-1 text-xs bg-green-200 text-green-700 rounded-full">
                {cartItems.length}
              </span>
            </div>
          </button>
        </div>

        {/* Transaction History Tab */}
        {activeTab === 'history' && (
          <div className="space-y-6">
            {selectedTransaction ? (
              /* Transaction Detail */
              <div className="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div className="p-6 bg-gray-50 border-b border-gray-200">
                  <div className="flex items-center justify-between">
                    <button
                      onClick={() => setSelectedTransaction(null)}
                      className="flex items-center space-x-2 text-red-600 hover:text-red-700 font-medium"
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
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                      <h3 className="text-lg font-semibold mb-4 text-gray-900">Informasi Pesanan</h3>
                      <div className="space-y-3 bg-gray-50 p-4 rounded-lg">
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
                            <span className="font-mono text-sm bg-yellow-100 px-2 py-1 rounded">
                              {selectedTransaction.trackingNumber}
                            </span>
                          </div>
                        )}
                      </div>
                    </div>

                    <div>
                      <h3 className="text-lg font-semibold mb-4 text-gray-900">Informasi Pembeli</h3>
                      <div className="space-y-3 bg-gray-50 p-4 rounded-lg">
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
                        <div className="flex justify-between">
                          <span className="text-gray-600">Pembayaran:</span>
                          <span className="font-medium">{selectedTransaction.paymentMethod}</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  {/* Items */}
                  <div>
                    <h3 className="text-lg font-semibold mb-4 text-gray-900">Item Pesanan</h3>
                    <div className="space-y-4">
                      {selectedTransaction.items.map((item, index) => (
                        <div key={index} className="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                          <div className="w-20 h-20 bg-white rounded-lg overflow-hidden">
                            <Image
                              src={item.image}
                              alt={item.name}
                              width={80}
                              height={80}
                              className="w-full h-full object-cover"
                            />
                          </div>
                          <div className="flex-1">
                            <h4 className="font-semibold text-gray-900">{item.name}</h4>
                            <div className="text-sm text-gray-600 space-y-1">
                              <p>Warna: {item.color}</p>
                              <p>Ukuran: {item.size}</p>
                              <p>Quantity: {item.quantity}x</p>
                            </div>
                          </div>
                          <div className="text-right">
                            <p className="font-semibold text-red-600">{item.price}</p>
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
                    <h3 className="text-lg font-semibold mb-4 text-gray-900">Alamat Pengiriman</h3>
                    <div className="bg-gray-50 p-4 rounded-lg">
                      <p className="text-gray-700">{selectedTransaction.shippingAddress}</p>
                    </div>
                  </div>
                </div>
              </div>
            ) : (
              /* Transaction List */
              <div className="grid gap-6">
                {transactions.length === 0 ? (
                  <div className="text-center py-16 bg-white rounded-2xl">
                    <div className="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                      <svg className="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                      </svg>
                    </div>
                    <h3 className="text-xl font-semibold text-gray-900 mb-2">Belum Ada Transaksi</h3>
                    <p className="text-gray-500">Riwayat transaksi Anda akan muncul di sini</p>
                  </div>
                ) : (
                  transactions.map((transaction) => (
                    <div
                      key={transaction.id}
                      onClick={() => setSelectedTransaction(transaction)}
                      className="bg-white rounded-2xl shadow-sm p-6 cursor-pointer hover:shadow-md transition-shadow"
                    >
                      <div className="flex items-center justify-between mb-4">
                        <div>
                          <h3 className="text-lg font-semibold text-gray-900">
                            Order #{transaction.id}
                          </h3>
                          <p className="text-sm text-gray-600">{formatDate(transaction.date)}</p>
                        </div>
                        <span className={`px-3 py-1 rounded-full text-sm font-medium border ${getStatusColor(transaction.status)}`}>
                          {getStatusText(transaction.status)}
                        </span>
                      </div>
                      
                      <div className="flex items-center space-x-4 mb-4">
                        {transaction.items.slice(0, 4).map((item, index) => (
                          <div key={index} className="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
                            <Image
                              src={item.image}
                              alt={item.name}
                              width={64}
                              height={64}
                              className="w-full h-full object-cover"
                            />
                          </div>
                        ))}
                        {transaction.items.length > 4 && (
                          <div className="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <span className="text-sm text-gray-600">+{transaction.items.length - 4}</span>
                          </div>
                        )}
                      </div>
                      
                      <div className="flex justify-between items-center">
                        <p className="text-sm text-gray-600">
                          {transaction.items.length} item • {transaction.customerInfo?.name}
                        </p>
                        <p className="text-xl font-bold text-red-600">
                          {formatPrice(transaction.total)}
                        </p>
                      </div>
                    </div>
                  ))
                )}
              </div>
            )}
          </div>
        )}

        {/* Checkout Tab */}
        {activeTab === 'checkout' && (
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {/* Customer Form */}
            <div className="lg:col-span-2">
              <div className="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div className="px-6 py-4 bg-gray-50 border-b border-gray-200">
                  <h2 className="text-xl font-semibold text-gray-900">Data Pembeli</h2>
                  <p className="text-sm text-gray-600">Lengkapi informasi untuk mengirim pesanan via WhatsApp</p>
                </div>

                <div className="p-6 space-y-6">
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                      <label className="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap *
                      </label>
                      <input
                        type="text"
                        value={customerInfo.name}
                        onChange={(e) => handleCustomerInfoChange('name', e.target.value)}
                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500"
                        placeholder="Masukkan nama lengkap"
                      />
                    </div>
                    
                    <div>
                      <label className="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Telefon *
                      </label>
                      <input
                        type="tel"
                        value={customerInfo.phone}
                        onChange={(e) => handleCustomerInfoChange('phone', e.target.value)}
                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500"
                        placeholder="08xxxxxxxxxx"
                      />
                    </div>
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">
                      Email *
                    </label>
                    <input
                      type="email"
                      value={customerInfo.email}
                      onChange={(e) => handleCustomerInfoChange('email', e.target.value)}
                      className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500"
                      placeholder="example@email.com"
                    />
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">
                      Alamat Lengkap *
                    </label>
                    <textarea
                      value={customerInfo.address}
                      onChange={(e) => handleCustomerInfoChange('address', e.target.value)}
                      rows={3}
                      className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500"
                      placeholder="Jalan, Nomor Rumah, RT/RW, Kelurahan, Kecamatan"
                    />
                  </div>

                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                      <label className="block text-sm font-medium text-gray-700 mb-2">
                        Kota *
                      </label>
                      <input
                        type="text"
                        value={customerInfo.city}
                        onChange={(e) => handleCustomerInfoChange('city', e.target.value)}
                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500"
                        placeholder="Jakarta"
                      />
                    </div>
                    
                    <div>
                      <label className="block text-sm font-medium text-gray-700 mb-2">
                        Kode Pos *
                      </label>
                      <input
                        type="text"
                        value={customerInfo.postalCode}
                        onChange={(e) => handleCustomerInfoChange('postalCode', e.target.value)}
                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500"
                        placeholder="12345"
                      />
                    </div>
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">
                      Catatan Tambahan
                    </label>
                    <textarea
                      value={customerInfo.notes}
                      onChange={(e) => handleCustomerInfoChange('notes', e.target.value)}
                      rows={2}
                      className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500"
                      placeholder="Catatan khusus untuk pesanan (opsional)"
                    />
                  </div>
                </div>
              </div>
            </div>

            {/* Order Summary */}
            <div className="lg:col-span-1">
              <div className="bg-white rounded-2xl shadow-sm overflow-hidden sticky top-4">
                <div className="px-6 py-4 bg-gray-50 border-b border-gray-200">
                  <h2 className="text-xl font-semibold text-gray-900">Ringkasan Pesanan</h2>
                </div>

                <div className="p-6">
                  {/* Cart Items */}
                  <div className="space-y-4 mb-6">
                    {cartItems.map((item, index) => (
                      <div key={index} className="flex items-center space-x-3">
                        <div className="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden">
                          <Image
                            src={item.image}
                            alt={item.name}
                            width={48}
                            height={48}
                            className="w-full h-full object-cover"
                          />
                        </div>
                        <div className="flex-1 min-w-0">
                          <h4 className="text-sm font-medium text-gray-900 truncate">{item.name}</h4>
                          <p className="text-xs text-gray-500">{item.color} • Size {item.size}</p>
                        </div>
                        <div className="text-right">
                          <p className="text-sm font-semibold text-gray-900">{item.quantity}x</p>
                          <p className="text-xs text-red-600">{item.price}</p>
                        </div>
                      </div>
                    ))}
                  </div>

                  {/* Price Breakdown */}
                  <div className="space-y-2 mb-6 pt-4 border-t border-gray-200">
                    <div className="flex justify-between">
                      <span className="text-gray-600">Subtotal</span>
                      <span className="font-medium">
                        {formatPrice(cartItems.reduce((sum, item) => sum + (item.originalPrice * item.quantity), 0))}
                      </span>
                    </div>
                    <div className="flex justify-between">
                      <span className="text-gray-600">Pajak (10%)</span>
                      <span className="font-medium">
                        {formatPrice(cartItems.reduce((sum, item) => sum + (item.originalPrice * item.quantity), 0) * 0.1)}
                      </span>
                    </div>
                    <div className="flex justify-between">
                      <span className="text-gray-600">Ongkir</span>
                      <span className="font-medium">{formatPrice(50000)}</span>
                    </div>
                    <div className="border-t border-gray-200 pt-2">
                      <div className="flex justify-between items-center">
                        <span className="text-lg font-semibold text-gray-900">Total</span>
                        <span className="text-xl font-bold text-red-600">
                          {formatPrice(
                            cartItems.reduce((sum, item) => sum + (item.originalPrice * item.quantity), 0) * 1.1 + 50000
                          )}
                        </span>
                      </div>
                    </div>
                  </div>

                  {/* WhatsApp Button */}
                  <button
                    onClick={handleSendToWhatsApp}
                    disabled={!isFormValid()}
                    className={`w-full py-4 rounded-xl font-semibold text-lg transition-all duration-200 flex items-center justify-center space-x-2 ${
                      isFormValid()
                        ? 'bg-green-600 text-white hover:bg-green-700 shadow-lg'
                        : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                    }`}
                  >
                    <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.570-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.106"/>
                    </svg>
                    <span>
                      {isFormValid() ? 'Kirim ke WhatsApp' : 'Lengkapi Data Dulu'}
                    </span>
                  </button>
                  
                  <div className="mt-4 text-center">
                    <div className="flex items-center justify-center space-x-2 text-sm text-gray-500">
                      <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      <span>Pesanan akan dikirim ke: +62 821-1142-4592</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        )}
      </div>

      {/* Footer */}
      <footer className="py-12 mt-16 text-white bg-red-800">
        <div className="container px-4 mx-auto">
          <div className="grid grid-cols-1 gap-8 md:grid-cols-3">
            {/* Brand */}
            <div>
              <h3 className="mb-4 text-2xl font-bold">VNY</h3>
              <p className="mb-2 text-sm text-white/80">+62 123 456 789</p>
              <p className="text-sm text-white/80">VNY@gmail.com</p>
              
              <div className="flex mt-4 space-x-4">
                <div className="flex items-center justify-center w-8 h-8 rounded-full cursor-pointer bg-white/20 hover:bg-white/30">
                  <span className="text-sm">f</span>
                </div>
                <div className="flex items-center justify-center w-8 h-8 rounded-full cursor-pointer bg-white/20 hover:bg-white/30">
                  <span className="text-sm">t</span>
                </div>
              </div>
            </div>

            {/* Quick Action */}
            <div>
              <h4 className="mb-4 font-semibold">Quick Action</h4>
              <div className="space-y-2 text-sm text-white/80">
                <div>Product</div>
                <div>Gallery</div>
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