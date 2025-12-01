'use client';

import { useState, useEffect } from 'react';
import CustomerReviewCards from '@/components/CustomerReviewCards';
import Link from 'next/link';

interface CustomerReview {
  id: number;
  customer_name: string;
  photo_url: string;
  review_text: string;
  rating: number;
  order_number: string;
  created_at: string;
  formatted_date: string;
}

export default function AllCustomerReviewsPage() {
  const [reviews, setReviews] = useState<CustomerReview[]>([]);
  const [loading, setLoading] = useState(true);
  const [stats, setStats] = useState({
    total: 0,
    averageRating: 0,
    fiveStars: 0,
    fourStars: 0,
    threeStars: 0,
    twoStars: 0,
    oneStar: 0
  });

  useEffect(() => {
    fetchAllReviews();
  }, []);

  const fetchAllReviews = async () => {
    try {
      const response = await fetch('http://localhost:8000/api/vny/reviews/approved');
      const data = await response.json();
      
      if (response.ok) {
        setReviews(data.reviews);
        calculateStats(data.reviews);
      }
    } catch (error) {
      console.error('Error fetching reviews:', error);
    } finally {
      setLoading(false);
    }
  };

  const calculateStats = (reviews: CustomerReview[]) => {
    const total = reviews.length;
    if (total === 0) return;

    const ratings = reviews.map(r => r.rating);
    const averageRating = ratings.reduce((sum, rating) => sum + rating, 0) / total;
    
    const ratingCounts = {
      fiveStars: ratings.filter(r => r === 5).length,
      fourStars: ratings.filter(r => r === 4).length,
      threeStars: ratings.filter(r => r === 3).length,
      twoStars: ratings.filter(r => r === 2).length,
      oneStar: ratings.filter(r => r === 1).length
    };

    setStats({
      total,
      averageRating: Math.round(averageRating * 10) / 10,
      ...ratingCounts
    });
  };

  const renderStars = (rating: number) => {
    return Array.from({ length: 5 }, (_, i) => (
      <span key={i} className={`text-xl ${i < rating ? 'text-yellow-400' : 'text-gray-300'}`}>
        ⭐
      </span>
    ));
  };

  const getRatingPercentage = (count: number) => {
    return stats.total > 0 ? Math.round((count / stats.total) * 100) : 0;
  };

  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-red-600 mx-auto mb-4"></div>
          <p className="text-red-600 font-medium">Loading all reviews...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <div className="bg-white shadow-sm">
        <div className="container mx-auto px-4 py-8">
          <div className="flex items-center justify-between">
            <div>
              <h1 className="text-4xl font-bold text-red-600 mb-2">All Customer Reviews</h1>
              <p className="text-gray-600">
                Semua review dari customer yang puas dengan VNY Store
              </p>
            </div>
            <Link 
              href="/customerreview"
              className="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
            >
              ← Back to Featured
            </Link>
          </div>
        </div>
      </div>

      <div className="container mx-auto px-4 py-12">
        {/* Statistics Overview */}
        <div className="bg-white rounded-lg shadow-lg p-8 mb-12">
          <h2 className="text-2xl font-bold text-gray-800 mb-6">Review Statistics</h2>
          
          <div className="grid md:grid-cols-2 gap-8">
            {/* Overall Stats */}
            <div className="text-center">
              <div className="text-6xl font-bold text-red-600 mb-2">{stats.averageRating}</div>
              <div className="flex justify-center mb-2">
                {renderStars(Math.round(stats.averageRating))}
              </div>
              <p className="text-gray-600">Based on {stats.total} reviews</p>
            </div>

            {/* Rating Breakdown */}
            <div className="space-y-3">
              {[
                { stars: 5, count: stats.fiveStars, label: 'Excellent' },
                { stars: 4, count: stats.fourStars, label: 'Very Good' },
                { stars: 3, count: stats.threeStars, label: 'Good' },
                { stars: 2, count: stats.twoStars, label: 'Fair' },
                { stars: 1, count: stats.oneStar, label: 'Poor' }
              ].map((item) => (
                <div key={item.stars} className="flex items-center gap-4">
                  <span className="text-sm font-medium w-16">{item.stars} stars</span>
                  <div className="flex-1 bg-gray-200 rounded-full h-3">
                    <div 
                      className="bg-yellow-400 h-3 rounded-full transition-all duration-500"
                      style={{ width: `${getRatingPercentage(item.count)}%` }}
                    ></div>
                  </div>
                  <span className="text-sm text-gray-600 w-16 text-right">
                    {getRatingPercentage(item.count)}%
                  </span>
                </div>
              ))}
            </div>
          </div>
        </div>

        {/* All Reviews Display */}
        {reviews.length > 0 ? (
          <>
            <div className="text-center mb-8">
              <h2 className="text-3xl font-bold text-gray-800 mb-4">
                All Customer Reviews ({stats.total})
              </h2>
              <p className="text-gray-600">
                Tidak ada effects pada halaman ini - hanya menampilkan semua review yang ada
              </p>
            </div>

            {/* Simple Grid Layout - No Effects */}
            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
              {reviews.map((review) => (
                <div key={review.id} className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                  {/* Review Image */}
                  <div className="h-64 bg-gray-200 relative">
                    {review.photo_url ? (
                      <img 
                        src={review.photo_url} 
                        alt={`Review by ${review.customer_name}`}
                        className="w-full h-full object-cover"
                      />
                    ) : (
                      <div className="w-full h-full flex items-center justify-center text-gray-400">
                        No Image
                      </div>
                    )}
                    
                    {/* Rating Badge */}
                    <div className="absolute top-4 right-4 bg-white bg-opacity-90 rounded-full px-3 py-1 flex items-center gap-1">
                      <span className="text-yellow-400">⭐</span>
                      <span className="font-semibold text-sm">{review.rating}</span>
                    </div>
                  </div>

                  {/* Review Content */}
                  <div className="p-6">
                    <div className="flex justify-between items-start mb-4">
                      <div>
                        <h3 className="font-semibold text-lg text-gray-800">
                          {review.customer_name}
                        </h3>
                        <p className="text-sm text-gray-500">
                          Order #{review.order_number}
                        </p>
                      </div>
                      <div className="text-right">
                        <p className="text-sm text-gray-500">
                          {review.formatted_date}
                        </p>
                      </div>
                    </div>

                    {/* Stars */}
                    <div className="flex mb-3">
                      {renderStars(review.rating)}
                    </div>

                    {/* Review Text */}
                    <p className="text-gray-700 text-sm leading-relaxed">
                      {review.review_text}
                    </p>
                  </div>
                </div>
              ))}
            </div>

            {/* Load More Button (for future implementation) */}
            <div className="text-center mt-12">
              <p className="text-gray-500 italic">
                Showing all {stats.total} approved reviews
              </p>
            </div>
          </>
        ) : (
          <div className="text-center py-16">
            <div className="text-gray-400 text-6xl mb-4">📝</div>
            <h3 className="text-2xl font-semibold text-gray-600 mb-4">No Reviews Yet</h3>
            <p className="text-gray-500 mb-8">
              Belum ada customer review yang disetujui untuk ditampilkan.
            </p>
            <Link 
              href="/"
              className="inline-block bg-red-600 text-white px-8 py-3 rounded-lg hover:bg-red-700 transition-colors"
            >
              Shop Now
            </Link>
          </div>
        )}
      </div>
    </div>
  );
}