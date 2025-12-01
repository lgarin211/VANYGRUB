'use client';

import React, { useState, useEffect } from 'react';
import Image from 'next/image';

interface TransactionItem {
  id: number;
  name: string;
  image: string;
  quantity: number;
  price: string;
  color: string;
  size: string | number;
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
}

interface TransactionModalProps {
  isOpen: boolean;
  onClose: () => void;
  transactions: Transaction[];
}

const TransactionModal: React.FC<TransactionModalProps> = ({
  isOpen,
  onClose,
  transactions,
}) => {
  const [isVisible, setIsVisible] = useState(false);
  const [selectedTab, setSelectedTab] = useState<'all' | 'pending' | 'completed'>('all');
  const [selectedTransaction, setSelectedTransaction] = useState<Transaction | null>(null);

  useEffect(() => {
    if (isOpen) {
      setIsVisible(true);
      document.body.style.overflow = 'hidden';
    } else {
      const timer = setTimeout(() => {
        setIsVisible(false);
        document.body.style.overflow = 'unset';
      }, 300);
      return () => clearTimeout(timer);
    }
    
    return () => {
      document.body.style.overflow = 'unset';
    };
  }, [isOpen]);

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
      year: 'numeric'
    });
  };

  const filteredTransactions = Array.isArray(transactions) ? transactions.filter(transaction => {
    if (selectedTab === 'pending') {
      return ['pending', 'processing', 'shipped'].includes(transaction.status);
    } else if (selectedTab === 'completed') {
      return ['delivered', 'cancelled'].includes(transaction.status);
    }
    return true;
  }) : [];

  if (!isVisible) return null;

  return (
    <div className={`fixed inset-0 z-50 transition-all duration-300 ${isOpen ? 'opacity-100' : 'opacity-0'}`}>
      {/* Backdrop */}
      <div 
        className="absolute inset-0 bg-black/50 backdrop-blur-sm"
        onClick={onClose}
      />
      
      {/* Modal */}
      <div className={`fixed top-0 right-0 h-full w-full max-w-2xl bg-white shadow-2xl transform transition-transform duration-300 ${
        isOpen ? 'translate-x-0' : 'translate-x-full'
      }`}>
        {/* Header */}
        <div className="flex items-center justify-between p-6 border-b border-gray-200 bg-red-800 text-white">
          <div className="flex items-center space-x-3">
            <div className="flex items-center justify-center w-10 h-10 bg-white/20 rounded-full">
              <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
            </div>
            <div>
              <h2 className="text-xl font-bold">Riwayat Transaksi</h2>
              <p className="text-white/70 text-sm">{Array.isArray(transactions) ? transactions.length : 0} transaksi</p>
            </div>
          </div>
          <button
            onClick={onClose}
            className="flex items-center justify-center w-8 h-8 bg-white/20 rounded-full hover:bg-white/30 transition-colors"
          >
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        {/* Tabs */}
        <div className="flex border-b border-gray-200">
          {[
            { key: 'all', label: 'Semua', count: Array.isArray(transactions) ? transactions.length : 0 },
            { key: 'pending', label: 'Berlangsung', count: Array.isArray(transactions) ? transactions.filter(t => ['pending', 'processing', 'shipped'].includes(t.status)).length : 0 },
            { key: 'completed', label: 'Selesai', count: Array.isArray(transactions) ? transactions.filter(t => ['delivered', 'cancelled'].includes(t.status)).length : 0 }
          ].map(tab => (
            <button
              key={tab.key}
              onClick={() => setSelectedTab(tab.key as any)}
              className={`flex-1 px-4 py-3 font-medium text-sm relative ${
                selectedTab === tab.key
                  ? 'text-red-600 border-b-2 border-red-600 bg-red-50'
                  : 'text-gray-600 hover:text-red-600 hover:bg-gray-50'
              } transition-colors`}
            >
              {tab.label}
              {tab.count > 0 && (
                <span className="ml-2 px-2 py-0.5 bg-gray-200 text-gray-700 rounded-full text-xs">
                  {tab.count}
                </span>
              )}
            </button>
          ))}
        </div>

        {/* Content */}
        <div className="flex-1 overflow-y-auto">
          {selectedTransaction ? (
            /* Transaction Detail */
            <div className="p-6">
              <div className="flex items-center justify-between mb-6">
                <button
                  onClick={() => setSelectedTransaction(null)}
                  className="flex items-center space-x-2 text-red-600 hover:text-red-700"
                >
                  <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                  </svg>
                  <span>Kembali</span>
                </button>
                <span className={`px-3 py-1 rounded-full text-sm font-medium border ${getStatusColor(selectedTransaction.status)}`}>
                  {getStatusText(selectedTransaction.status)}
                </span>
              </div>

              <div className="space-y-6">
                <div>
                  <h3 className="text-lg font-semibold mb-2">Detail Pesanan</h3>
                  <div className="bg-gray-50 p-4 rounded-lg space-y-2">
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
                        <span className="font-mono text-sm">{selectedTransaction.trackingNumber}</span>
                      </div>
                    )}
                  </div>
                </div>

                <div>
                  <h3 className="text-lg font-semibold mb-3">Items</h3>
                  <div className="space-y-3">
                    {selectedTransaction.items.map((item, index) => (
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
                          <h4 className="font-medium">{item.name}</h4>
                          <p className="text-sm text-gray-600">
                            {item.color} • Ukuran {item.size} • Qty: {item.quantity}
                          </p>
                          <p className="text-sm font-semibold text-red-600">{item.price}</p>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>

                <div>
                  <h3 className="text-lg font-semibold mb-2">Alamat Pengiriman</h3>
                  <div className="bg-gray-50 p-4 rounded-lg">
                    <p className="text-gray-700">{selectedTransaction.shippingAddress}</p>
                  </div>
                </div>

                <div>
                  <h3 className="text-lg font-semibold mb-2">Metode Pembayaran</h3>
                  <div className="bg-gray-50 p-4 rounded-lg">
                    <p className="text-gray-700">{selectedTransaction.paymentMethod}</p>
                  </div>
                </div>
              </div>
            </div>
          ) : (
            /* Transaction List */
            <div className="p-6">
              {filteredTransactions.length === 0 ? (
                <div className="text-center py-12">
                  <div className="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg className="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                  </div>
                  <h3 className="text-xl font-semibold text-gray-900 mb-2">Belum Ada Transaksi</h3>
                  <p className="text-gray-500">Transaksi Anda akan muncul di sini setelah melakukan pembelian.</p>
                </div>
              ) : (
                <div className="space-y-4">
                  {filteredTransactions.map((transaction) => (
                    <div
                      key={transaction.id}
                      onClick={() => setSelectedTransaction(transaction)}
                      className="bg-gray-50 rounded-lg p-4 cursor-pointer hover:bg-gray-100 transition-colors"
                    >
                      <div className="flex items-center justify-between mb-3">
                        <div>
                          <p className="font-semibold text-gray-900">Order #{transaction.id}</p>
                          <p className="text-sm text-gray-600">{formatDate(transaction.date)}</p>
                        </div>
                        <span className={`px-3 py-1 rounded-full text-sm font-medium border ${getStatusColor(transaction.status)}`}>
                          {getStatusText(transaction.status)}
                        </span>
                      </div>
                      
                      <div className="flex items-center space-x-3 mb-3">
                        {transaction.items.slice(0, 3).map((item, index) => (
                          <div key={index} className="w-12 h-12 bg-white rounded-lg overflow-hidden">
                            <Image
                              src={item.image}
                              alt={item.name}
                              width={48}
                              height={48}
                              className="w-full h-full object-cover"
                            />
                          </div>
                        ))}
                        {transaction.items.length > 3 && (
                          <div className="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                            <span className="text-xs text-gray-600">+{transaction.items.length - 3}</span>
                          </div>
                        )}
                      </div>
                      
                      <div className="flex justify-between items-center">
                        <p className="text-sm text-gray-600">
                          {transaction.items.length} item{transaction.items.length > 1 ? 's' : ''}
                        </p>
                        <p className="font-bold text-red-600">{formatPrice(transaction.total)}</p>
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default TransactionModal;