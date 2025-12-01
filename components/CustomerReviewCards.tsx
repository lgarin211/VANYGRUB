'use client';

import { useEffect, useState, useCallback } from 'react';
import Image from 'next/image';
import './customer-review-cards.css';

interface CustomerReview {
  id: number;
  customer_name: string;
  photo_url: string;
  review_text: string;
  rating: number;
  order_number: string;
  created_at: string;
  formatted_date: string;
  is_featured?: boolean;
}

interface CustomerReviewCardsProps {
  featured?: boolean;
  limit?: number;
  className?: string;
}

export default function CustomerReviewCards({ featured = false, limit, className = '' }: CustomerReviewCardsProps) {
  const [reviews, setReviews] = useState<CustomerReview[]>([]);
  const [loading, setLoading] = useState(true);

  const fetchReviews = useCallback(async () => {
    try {
      const endpoint = featured ? 'featured' : 'approved';
      const params = limit ? `?limit=${limit}` : '';
      
      const response = await fetch(`https://vanyadmin.progesio.my.id/api/vny/reviews/${endpoint}${params}`);
      const data = await response.json();
      
      if (response.ok) {
        setReviews(data.reviews);
      }
    } catch (error) {
      console.error('Error fetching reviews:', error);
    } finally {
      setLoading(false);
    }
  }, [featured, limit]);

  useEffect(() => {
    fetchReviews();
  }, [fetchReviews]);

  const truncateText = (text: string, maxLength: number = 150) => {
    if (text.length <= maxLength) return text;
    return text.substr(0, maxLength) + '...';
  };

  const renderStars = (rating: number) => {
    return Array.from({ length: 5 }, (_, i) => (
      <span key={i} className={`star ${i < rating ? 'filled' : ''}`}>★</span>
    ));
  };

  if (loading) {
    return (
      <div className="loading-container">
        <div className="loading-spinner"></div>
        <p>Memuat review...</p>
      </div>
    );
  }

  if (reviews.length === 0) {
    return (
      <div className="no-reviews">
        <p>Belum ada customer review yang tersedia.</p>
      </div>
    );
  }

  return (
    <div className={`customer-reviews-container ${className}`}>
      <div className="row">
        {reviews.map((review, index) => {
          const reviewDate = new Date(review.created_at);
          
          return (
            <div key={review.id} className="example-2 card">
              <div className="wrapper">
                <div className="header">
                  <div className="date">
                    <span className="day">{reviewDate.getDate()}</span>
                    <span className="month">{reviewDate.toLocaleDateString('id-ID', { month: 'short' })}</span>
                    <span className="year">{reviewDate.getFullYear()}</span>
                  </div>
                </div>
                <div 
                  className="card-background"
                  style={{
                    backgroundImage: `url(${review.photo_url})`,
                  }}
                ></div>
                <div className="data">
                  <div className="content">
                    <span className="author">{review.customer_name}</span>
                    <h1 className="title">
                      <a href="#">Review Pesanan #{review.order_number}</a>
                    </h1>
                    <div className="rating-display">
                      {renderStars(review.rating)}
                    </div>
                    <p className="text">{truncateText(review.review_text)}</p>
                  </div>
                </div>
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}