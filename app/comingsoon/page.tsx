'use client';

import { useState, useEffect, useMemo } from 'react';
import Link from 'next/link';

export default function ComingSoonPage() {
  const [timeLeft, setTimeLeft] = useState({
    days: 0,
    hours: 0,
    minutes: 0,
    seconds: 0
  });
  
  const [email, setEmail] = useState('');
  const [isSubscribed, setIsSubscribed] = useState(false);

  // Set target date (30 days from now)
  const targetDate = useMemo(() => {
    const date = new Date();
    date.setDate(date.getDate() + 30);
    return date;
  }, []);

  useEffect(() => {
    const timer = setInterval(() => {
      const now = new Date().getTime();
      const target = targetDate.getTime();
      const difference = target - now;

      if (difference > 0) {
        const days = Math.floor(difference / (1000 * 60 * 60 * 24));
        const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((difference % (1000 * 60)) / 1000);

        setTimeLeft({ days, hours, minutes, seconds });
      } else {
        setTimeLeft({ days: 0, hours: 0, minutes: 0, seconds: 0 });
      }
    }, 1000);

    return () => clearInterval(timer);
  }, [targetDate]);

  const handleSubscribe = (e: React.FormEvent) => {
    e.preventDefault();
    if (email) {
      // Here you would typically send the email to your backend
      console.log('Subscribing email:', email);
      setIsSubscribed(true);
      setEmail('');
    }
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-red-900 via-red-800 to-red-700 flex flex-col items-center justify-center text-white relative overflow-hidden">
      {/* Background Effects */}
      <div className="absolute inset-0 bg-black bg-opacity-20"></div>
      <div className="absolute top-0 left-0 w-full h-full">
        <div className="absolute top-1/4 left-1/4 w-96 h-96 bg-red-600 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse"></div>
        <div className="absolute top-3/4 right-1/4 w-96 h-96 bg-red-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse animation-delay-2000"></div>
      </div>

      <div className="relative z-10 text-center max-w-4xl mx-auto px-4">
        {/* Header */}
        <div className="mb-8">
          <Link href="/vny" className="text-4xl font-bold tracking-wider mb-4 inline-block hover:text-red-300 transition-colors">
            VNY
          </Link>
          <h1 className="text-6xl md:text-7xl font-bold mb-4 bg-gradient-to-r from-white to-red-200 bg-clip-text text-transparent">
            Coming Soon
          </h1>
          <p className="text-xl md:text-2xl text-red-100 mb-8 max-w-2xl mx-auto">
            Something amazing is on the way. We&apos;re working hard to bring you an incredible experience.
          </p>
        </div>

        {/* Countdown Timer */}
        <div className="mb-12">
          <div className="flex flex-wrap justify-center gap-4 md:gap-8">
            {[
              { label: 'Days', value: timeLeft.days },
              { label: 'Hours', value: timeLeft.hours },
              { label: 'Minutes', value: timeLeft.minutes },
              { label: 'Seconds', value: timeLeft.seconds }
            ].map((item, index) => (
              <div key={index} className="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4 md:p-6 min-w-[80px] md:min-w-[120px]">
                <div className="text-3xl md:text-5xl font-bold mb-2">
                  {item.value.toString().padStart(2, '0')}
                </div>
                <div className="text-sm md:text-base text-red-200 uppercase tracking-wider">
                  {item.label}
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Subscription Form */}
        <div className="mb-12">
          <h2 className="text-2xl md:text-3xl font-semibold mb-6">Get Notified When We Launch</h2>
          
          {!isSubscribed ? (
            <form onSubmit={handleSubscribe} className="max-w-md mx-auto">
              <div className="flex flex-col sm:flex-row gap-4">
                <input
                  type="email"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  placeholder="Enter your email address"
                  className="flex-1 px-4 py-3 rounded-lg bg-white bg-opacity-10 backdrop-blur-sm border border-white border-opacity-20 text-white placeholder-red-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-30"
                  required
                />
                <button
                  type="submit"
                  className="px-6 py-3 bg-white text-red-800 font-semibold rounded-lg hover:bg-red-100 transition-colors duration-200"
                >
                  Notify Me
                </button>
              </div>
            </form>
          ) : (
            <div className="bg-green-600 bg-opacity-20 border border-green-400 rounded-lg p-4 max-w-md mx-auto">
              <p className="text-green-200">
                ✅ Thank you! We&apos;ll notify you when we launch.
              </p>
            </div>
          )}
        </div>

        {/* Features Preview */}
        <div className="mb-12">
          <h3 className="text-xl md:text-2xl font-semibold mb-6">What to Expect</h3>
          <div className="grid md:grid-cols-3 gap-6">
            <div className="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-6">
              <div className="text-3xl mb-4">🛍️</div>
              <h4 className="font-semibold mb-2">Premium Products</h4>
              <p className="text-red-200 text-sm">Curated collection of high-quality lifestyle products</p>
            </div>
            <div className="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-6">
              <div className="text-3xl mb-4">⚡</div>
              <h4 className="font-semibold mb-2">Fast Experience</h4>
              <p className="text-red-200 text-sm">Lightning-fast browsing and seamless checkout process</p>
            </div>
            <div className="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-6">
              <div className="text-3xl mb-4">🎯</div>
              <h4 className="font-semibold mb-2">Personalized</h4>
              <p className="text-red-200 text-sm">Tailored recommendations based on your preferences</p>
            </div>
          </div>
        </div>

        {/* Social Links */}
        <div className="mb-8">
          <p className="text-red-200 mb-4">Follow us for updates</p>
          <div className="flex justify-center gap-4">
            <a href="#" className="bg-white bg-opacity-10 backdrop-blur-sm p-3 rounded-full hover:bg-opacity-20 transition-colors">
              <span className="text-xl">📘</span>
            </a>
            <a href="#" className="bg-white bg-opacity-10 backdrop-blur-sm p-3 rounded-full hover:bg-opacity-20 transition-colors">
              <span className="text-xl">📷</span>
            </a>
            <a href="#" className="bg-white bg-opacity-10 backdrop-blur-sm p-3 rounded-full hover:bg-opacity-20 transition-colors">
              <span className="text-xl">🐦</span>
            </a>
          </div>
        </div>

        {/* Back to Home */}
        <div>
          <Link 
            href="/vny" 
            className="inline-block bg-white bg-opacity-10 backdrop-blur-sm px-6 py-3 rounded-lg border border-white border-opacity-20 hover:bg-opacity-20 transition-colors"
          >
            ← Back to Home
          </Link>
        </div>
      </div>
    </div>
  );
}