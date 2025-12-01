'use client';

import React, { useState, useEffect } from 'react';
import Image from 'next/image';
import Link from 'next/link';
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

const CartPage: React.FC = () => {
  const [cartItems, setCartItems] = useState<CartItem[]>([]);
  const [promoCode, setPromoCode] = useState('');
  const [appliedPromo, setAppliedPromo] = useState<string | null>(null);
  const [showCheckoutModal, setShowCheckoutModal] = useState(false);
  const [customerInfo, setCustomerInfo] = useState<CustomerInfo>({
    name: '',
    phone: '',
    email: '',
    address: '',
    city: '',
    postalCode: '',
    notes: ''
  });

  // Demo cart data - in real app this would come from context/state management
  useEffect(() => {
    const demoItems: CartItem[] = [
      {
        id: 1,
        name: "Air Jordan 1 Retro",
        price: "Rp. 5.000.000",
        originalPrice: 5000000,
        image: "/temp/nike-just-do-it(6).jpg",
        color: "Black/Red",
        size: 42,
        quantity: 1,
        discount: "10%"
      },
      {
        id: 2,
        name: "Nike Dunk Low",
        price: "Rp. 4.500.000",
        originalPrice: 4500000,
        image: "/temp/nike-just-do-it(7).jpg",
        color: "Panda (Black/White)",
        size: 43,
        quantity: 2
      },
      {
        id: 3,
        name: "Converse Chuck 70",
        price: "Rp. 3.800.000",
        originalPrice: 3800000,
        image: "/temp/nike-just-do-it(8).jpg",
        color: "Vintage Khaki/Brown",
        size: 41,
        quantity: 1,
        discount: "15%"
      }
    ];
    setCartItems(demoItems);
  }, []);

  const handleUpdateQuantity = (id: number, newQuantity: number) => {
    if (newQuantity < 1) return;
    setCartItems(prev => 
      prev.map(item => item.id === id ? { ...item, quantity: newQuantity } : item)
    );
  };

  const handleRemoveItem = (id: number) => {
    setCartItems(prev => prev.filter(item => item.id !== id));
  };

  const handleApplyPromo = () => {
    if (promoCode.toLowerCase() === 'vny10') {
      setAppliedPromo('VNY10');
      alert('Promo code applied! 10% extra discount');
    } else if (promoCode.toLowerCase() === 'newuser') {
      setAppliedPromo('NEWUSER');
      alert('Welcome! 15% discount for new users');
    } else {
      alert('Invalid promo code');
    }
  };

  const handleRemovePromo = () => {
    setAppliedPromo(null);
    setPromoCode('');
  };

  // Calculate totals
  const subtotal = cartItems.reduce((sum, item) => sum + (item.originalPrice * item.quantity), 0);
  const itemDiscount = cartItems.reduce((sum, item) => {
    if (item.discount) {
      const discountPercent = parseInt(item.discount.replace('%', '')) / 100;
      return sum + (item.originalPrice * item.quantity * discountPercent);
    }
    return sum;
  }, 0);
  
  const promoDiscount = appliedPromo === 'VNY10' ? subtotal * 0.1 : 
                      appliedPromo === 'NEWUSER' ? subtotal * 0.15 : 0;
  
  const tax = (subtotal - itemDiscount - promoDiscount) * 0.1;
  const shipping = cartItems.length > 0 ? 50000 : 0;
  const total = subtotal - itemDiscount - promoDiscount + tax + shipping;

  const formatPrice = (price: number) => {
    return `Rp ${price.toLocaleString('id-ID')}`;
  };

  const handleCustomerInfoChange = (field: keyof CustomerInfo, value: string) => {
    setCustomerInfo(prev => ({
      ...prev,
      [field]: value
    }));
  };

  const generateUniqueCode = () => {
    // Generate unique order code: VNY + timestamp + random
    const timestamp = Date.now().toString().slice(-8);
    const random = Math.random().toString(36).substring(2, 6).toUpperCase();
    return `VNY${timestamp}${random}`;
  };

  const generateWhatsAppMessage = (orderCode: string) => {
    const trackingUrl = `${window.location.origin}/checkout/${orderCode}`;
    
    let message = `🛒 *PESANAN BARU VNY STORE*\n\n`;
    message += `📋 *Kode Pesanan:* ${orderCode}\n\n`;
    message += `🔗 *LINK CEK PESANAN (KLIK DI SINI):*\n`;
    message += `${trackingUrl}\n\n`;
    message += `📱 *Atau salin link ini untuk melihat receipt:*\n`;
    message += `${trackingUrl}\n\n`;
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
    if (itemDiscount > 0) {
      message += `Diskon Produk: -${formatPrice(itemDiscount)}\n`;
    }
    if (promoDiscount > 0) {
      message += `Diskon Promo (${appliedPromo}): -${formatPrice(promoDiscount)}\n`;
    }
    message += `Pajak (PPN 10%): ${formatPrice(tax)}\n`;
    message += `Ongkir: ${formatPrice(shipping)}\n`;
    message += `*TOTAL: ${formatPrice(total)}*\n\n`;
    message += `📅 Tanggal Pesan: ${new Date().toLocaleDateString('id-ID', { 
      day: 'numeric', 
      month: 'long', 
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    })}\n\n`;
    message += `✅ *CARA CEK PESANAN:*\n`;
    message += `1. Klik link di atas\n`;
    message += `2. Atau salin dan buka di browser\n`;
    message += `3. Lihat status real-time pesanan Anda\n`;
    message += `4. Print receipt untuk arsip\n\n`;
    message += `Mohon konfirmasi pesanan ini. Terima kasih! 🙏`;

    return encodeURIComponent(message);
  };

  const handleSendToWhatsApp = () => {
    if (!customerInfo.name || !customerInfo.phone || !customerInfo.address || !customerInfo.city || !customerInfo.postalCode) {
      alert('Mohon lengkapi semua data pembeli yang wajib diisi!');
      return;
    }

    // Generate unique order code
    const orderCode = generateUniqueCode();
    
    // Store order data in localStorage for receipt page
    const orderData = {
      orderCode,
      customerInfo,
      items: cartItems,
      pricing: {
        subtotal,
        itemDiscount,
        promoDiscount,
        appliedPromo,
        tax,
        shipping,
        total
      },
      orderDate: new Date().toISOString(),
      status: 'pending'
    };
    
    localStorage.setItem(`order_${orderCode}`, JSON.stringify(orderData));

    const whatsappNumber = '6282111424592';
    const message = generateWhatsAppMessage(orderCode);
    const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${message}`;
    
    // Open WhatsApp with the message
    window.open(whatsappUrl, '_blank');
    
    // Show success message with order code
    alert(`✅ Pesanan berhasil dibuat!\n\nKode Pesanan: ${orderCode}\n\nSilakan cek WhatsApp dan gunakan link untuk melacak pesanan Anda.`);
    
    // Close checkout modal and clear cart
    setShowCheckoutModal(false);
    setCartItems([]);
    
    // Reset customer info
    setCustomerInfo({
      name: '',
      phone: '',
      email: '',
      address: '',
      city: '',
      postalCode: '',
      notes: ''
    });
  };

  const isFormValid = () => {
    return customerInfo.name && customerInfo.phone && customerInfo.email && 
           customerInfo.address && customerInfo.city && customerInfo.postalCode;
  };

  return (
    <div className="min-h-screen bg-gray-50">
      <Header />

      <div className="container px-4 py-4 md:py-8 mx-auto">
        {/* Breadcrumb */}
        <div className="mb-6 md:mb-8">
          <div className="flex items-center space-x-2 text-xs md:text-sm">
            <Link href="/vny" className="text-red-600 hover:text-red-700">Home</Link>
            <span className="text-gray-400">/</span>
            <span className="text-gray-600">Keranjang Belanja</span>
          </div>
          <h1 className="mt-3 md:mt-4 text-2xl md:text-3xl font-bold text-gray-900">Keranjang Belanja</h1>
          <p className="text-sm md:text-base text-gray-600">Kelola produk yang ingin Anda beli</p>
        </div>

        {cartItems.length === 0 ? (
          /* Empty Cart */
          <div className="flex flex-col items-center justify-center py-12 md:py-16 text-center bg-white rounded-xl md:rounded-2xl px-4">
            <div className="w-24 h-24 md:w-32 md:h-32 mb-4 md:mb-6 bg-gray-100 rounded-full flex items-center justify-center">
              <svg className="w-12 h-12 md:w-16 md:h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13v6a2 2 0 002 2h10a2 2 0 002-2v-6m-10 0V9a2 2 0 012-2h6a2 2 0 012 2v4" />
              </svg>
            </div>
            <h2 className="mb-3 md:mb-4 text-xl md:text-2xl font-bold text-gray-900">Keranjang Kosong</h2>
            <p className="mb-6 md:mb-8 text-sm md:text-base text-gray-500 max-w-md">
              Belum ada produk yang ditambahkan ke keranjang. Mulai berbelanja dan temukan sepatu impian Anda!
            </p>
            <Link 
              href="/vny/product"
              className="px-6 md:px-8 py-3 md:py-4 text-sm md:text-base text-white bg-red-600 rounded-lg md:rounded-xl font-semibold hover:bg-red-700 transition-colors shadow-lg"
            >
              Mulai Belanja
            </Link>
          </div>
        ) : (
          /* Cart Content */
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-8">
            {/* Cart Items */}
            <div className="lg:col-span-2">
              <div className="bg-white rounded-xl md:rounded-2xl shadow-sm overflow-hidden">
                {/* Header */}
                <div className="px-4 md:px-6 py-3 md:py-4 bg-gray-50 border-b border-gray-200">
                  <div className="flex items-center justify-between">
                    <h2 className="text-lg md:text-xl font-semibold text-gray-900">
                      Item dalam Keranjang ({cartItems.reduce((sum, item) => sum + item.quantity, 0)})
                    </h2>
                    <button 
                      onClick={() => setCartItems([])}
                      className="text-xs md:text-sm text-red-600 hover:text-red-700 font-medium"
                    >
                      Hapus Semua
                    </button>
                  </div>
                </div>

                {/* Items List */}
                <div className="divide-y divide-gray-200">
                  {cartItems.map((item) => (
                    <div key={`${item.id}-${item.color}-${item.size}`} className="p-6">
                      <div className="flex items-start space-x-4">
                        {/* Product Image */}
                        <div className="flex-shrink-0">
                          <div className="w-24 h-24 bg-gray-100 rounded-xl overflow-hidden">
                            <Image
                              src={item.image}
                              alt={item.name}
                              width={96}
                              height={96}
                              className="w-full h-full object-cover"
                            />
                          </div>
                        </div>

                        {/* Product Info */}
                        <div className="flex-1 min-w-0">
                          <div className="flex items-start justify-between">
                            <div className="flex-1 pr-2">
                              <h3 className="text-base md:text-lg font-semibold text-gray-900 mb-1 line-clamp-2">
                                <Link href={`/vny/product/${item.id}`} className="hover:text-red-600">
                                  {item.name}
                                </Link>
                              </h3>
                              <div className="flex flex-col md:flex-row md:items-center md:space-x-4 text-xs md:text-sm text-gray-600 mb-2 space-y-1 md:space-y-0">
                                <span>Warna: <span className="font-medium">{item.color}</span></span>
                                <span>Ukuran: <span className="font-medium">{item.size}</span></span>
                              </div>
                              <div className="flex items-center space-x-2">
                                <span className="text-base md:text-lg font-bold text-red-600">{item.price}</span>
                                {item.discount && (
                                  <span className="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                    {item.discount} OFF
                                  </span>
                                )}
                              </div>
                            </div>
                            
                            {/* Remove Button */}
                            <button
                              onClick={() => handleRemoveItem(item.id)}
                              className="p-1 md:p-2 text-gray-400 hover:text-red-500 rounded-lg hover:bg-red-50 transition-colors flex-shrink-0"
                            >
                              <svg className="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                              </svg>
                            </button>
                          </div>

                          {/* Quantity Controls */}
                          <div className="flex flex-col md:flex-row md:items-center md:justify-between mt-3 md:mt-4 space-y-3 md:space-y-0">
                            <div className="flex items-center space-x-3">
                              <span className="text-xs md:text-sm font-medium text-gray-700">Jumlah:</span>
                              <div className="flex items-center border border-gray-300 rounded-lg">
                                <button
                                  onClick={() => handleUpdateQuantity(item.id, item.quantity - 1)}
                                  className="px-2 md:px-3 py-1 md:py-2 text-sm md:text-base text-gray-600 hover:text-gray-800 hover:bg-gray-50"
                                >
                                  −
                                </button>
                                <span className="px-3 md:px-4 py-1 md:py-2 text-sm md:text-base font-medium text-gray-900 border-x border-gray-300 min-w-[2.5rem] md:min-w-[3rem] text-center">
                                  {item.quantity}
                                </span>
                                <button
                                  onClick={() => handleUpdateQuantity(item.id, item.quantity + 1)}
                                  className="px-2 md:px-3 py-1 md:py-2 text-sm md:text-base text-gray-600 hover:text-gray-800 hover:bg-gray-50"
                                >
                                  +
                                </button>
                              </div>
                            </div>
                            
                            <div className="text-right">
                              <div className="text-xs md:text-sm text-gray-500">Subtotal</div>
                              <div className="text-base md:text-lg font-bold text-gray-900">
                                {formatPrice(item.originalPrice * item.quantity)}
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              </div>

              {/* Continue Shopping */}
              <div className="mt-6">
                <Link 
                  href="/vny/product"
                  className="inline-flex items-center space-x-2 text-red-600 hover:text-red-700 font-medium"
                >
                  <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                  </svg>
                  <span>Lanjut Belanja</span>
                </Link>
              </div>
            </div>

            {/* Order Summary */}
            <div className="lg:col-span-1">
              <div className="bg-white rounded-2xl shadow-sm overflow-hidden sticky top-4">
                {/* Header */}
                <div className="px-6 py-4 bg-gray-50 border-b border-gray-200">
                  <h2 className="text-xl font-semibold text-gray-900">Ringkasan Pesanan</h2>
                </div>

                <div className="p-6">
                  {/* Promo Code */}
                  <div className="mb-6">
                    <label className="block text-sm font-medium text-gray-700 mb-2">
                      Kode Promo
                    </label>
                    <div className="flex space-x-2">
                      <input
                        type="text"
                        value={promoCode}
                        onChange={(e) => setPromoCode(e.target.value)}
                        placeholder="Masukkan kode promo"
                        className="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500"
                        disabled={appliedPromo !== null}
                      />
                      {appliedPromo ? (
                        <button
                          onClick={handleRemovePromo}
                          className="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors"
                        >
                          Hapus
                        </button>
                      ) : (
                        <button
                          onClick={handleApplyPromo}
                          className="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                        >
                          Gunakan
                        </button>
                      )}
                    </div>
                    {appliedPromo && (
                      <div className="mt-2 p-2 bg-green-50 border border-green-200 rounded-lg">
                        <div className="flex items-center space-x-2">
                          <svg className="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                          </svg>
                          <span className="text-sm text-green-700 font-medium">
                            Kode promo {appliedPromo} diterapkan
                          </span>
                        </div>
                      </div>
                    )}
                  </div>

                  {/* Price Breakdown */}
                  <div className="space-y-3 mb-6">
                    <div className="flex justify-between">
                      <span className="text-gray-600">Subtotal ({cartItems.reduce((sum, item) => sum + item.quantity, 0)} item)</span>
                      <span className="font-medium">{formatPrice(subtotal)}</span>
                    </div>
                    
                    {itemDiscount > 0 && (
                      <div className="flex justify-between text-green-600">
                        <span>Diskon produk</span>
                        <span>-{formatPrice(itemDiscount)}</span>
                      </div>
                    )}
                    
                    {promoDiscount > 0 && (
                      <div className="flex justify-between text-green-600">
                        <span>Diskon promo ({appliedPromo})</span>
                        <span>-{formatPrice(promoDiscount)}</span>
                      </div>
                    )}
                    
                    <div className="flex justify-between">
                      <span className="text-gray-600">Pajak (PPN 10%)</span>
                      <span className="font-medium">{formatPrice(tax)}</span>
                    </div>
                    
                    <div className="flex justify-between">
                      <span className="text-gray-600">Ongkos kirim</span>
                      <span className="font-medium">{formatPrice(shipping)}</span>
                    </div>
                    
                    <div className="border-t border-gray-200 pt-3">
                      <div className="flex justify-between items-center">
                        <span className="text-lg font-semibold text-gray-900">Total</span>
                        <span className="text-2xl font-bold text-red-600">{formatPrice(total)}</span>
                      </div>
                    </div>
                  </div>

                  {/* Checkout Button */}
                  <button
                    onClick={() => setShowCheckoutModal(true)}
                    className="w-full bg-red-600 text-white py-4 rounded-xl font-semibold text-lg hover:bg-red-700 transition-colors shadow-lg"
                  >
                    Checkout Sekarang
                  </button>
                  
                  <div className="mt-4 text-center">
                    <div className="flex items-center justify-center space-x-2 text-sm text-gray-500">
                      <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      <span>Pembayaran aman dengan SSL</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        )}
      </div>

      {/* Checkout Modal */}
      {showCheckoutModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
          <div className="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div className="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 rounded-t-2xl">
              <div className="flex items-center justify-between">
                <h2 className="text-2xl font-bold text-gray-900">Checkout - Data Pembeli</h2>
                <button
                  onClick={() => setShowCheckoutModal(false)}
                  className="p-2 hover:bg-gray-100 rounded-lg transition-colors"
                >
                  <svg className="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 p-6">
              {/* Customer Form */}
              <div className="lg:col-span-2">
                <div className="space-y-6">
                  <div>
                    <h3 className="text-lg font-semibold text-gray-900 mb-4">Informasi Pembeli</h3>
                    <p className="text-sm text-gray-600 mb-6">
                      Lengkapi data di bawah untuk mengirim pesanan via WhatsApp ke admin VNY Store
                    </p>
                  </div>

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
                        required
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
                        required
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
                      required
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
                      required
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
                        required
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
                        required
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

              {/* Order Summary */}
              <div className="lg:col-span-1">
                <div className="bg-gray-50 rounded-2xl p-6 sticky top-4">
                  <h3 className="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h3>
                  
                  {/* Cart Items Summary */}
                  <div className="space-y-3 mb-6">
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
                    <div className="flex justify-between text-sm">
                      <span className="text-gray-600">Subtotal</span>
                      <span className="font-medium">{formatPrice(subtotal)}</span>
                    </div>
                    {itemDiscount > 0 && (
                      <div className="flex justify-between text-sm text-green-600">
                        <span>Diskon Produk</span>
                        <span>-{formatPrice(itemDiscount)}</span>
                      </div>
                    )}
                    {promoDiscount > 0 && (
                      <div className="flex justify-between text-sm text-green-600">
                        <span>Diskon Promo ({appliedPromo})</span>
                        <span>-{formatPrice(promoDiscount)}</span>
                      </div>
                    )}
                    <div className="flex justify-between text-sm">
                      <span className="text-gray-600">Pajak (PPN 10%)</span>
                      <span className="font-medium">{formatPrice(tax)}</span>
                    </div>
                    <div className="flex justify-between text-sm">
                      <span className="text-gray-600">Ongkir</span>
                      <span className="font-medium">{formatPrice(shipping)}</span>
                    </div>
                    <div className="border-t border-gray-200 pt-2">
                      <div className="flex justify-between items-center">
                        <span className="font-semibold text-gray-900">Total</span>
                        <span className="text-xl font-bold text-red-600">{formatPrice(total)}</span>
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
        </div>
      )}

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

export default CartPage;