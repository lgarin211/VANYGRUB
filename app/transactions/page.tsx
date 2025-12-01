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
  
  return (
    <div className="min-h-screen bg-gray-50">
      <Header />
      <div className="container px-4 py-8 mx-auto">
        <h1 className="text-2xl font-bold">Transactions Page</h1>
        <p>Test page to check if syntax is working</p>
      </div>
    </div>
  );
};

export default TransactionsPage;