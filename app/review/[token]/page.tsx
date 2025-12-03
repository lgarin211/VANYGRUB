'use client';

import { useState, useEffect } from 'react';
import { useParams, useRouter } from 'next/navigation';
import SafeImage from '../../../components/SafeImage';

interface OrderItem {
  id: number;
  product?: {
    name?: string;
    image?: string;
  };
  quantity: number;
  price: number;
}

interface Order {
  id: number;
  order_number?: string;
  customer_name?: string;
  total_amount?: number;
  created_at?: string;
}

interface SubmittedReview {
  id: number;
  customer_name: string;
  photo_url: string;
  review_text: string;
  rating: number;
  order_number?: string;
  created_at: string;
  formatted_date?: string;
}

interface ReviewData {
  review_token: string;
  order?: Order;
  customer_name?: string;
  order_items?: OrderItem[];
  message?: string;
  review?: SubmittedReview;
}

export default function ReviewForm() {
  const params = useParams();
  const router = useRouter();
  const [reviewData, setReviewData] = useState<ReviewData | null>(null);
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState(false);
  const [isReviewSubmitted, setIsReviewSubmitted] = useState(false);
  const [submittedReview, setSubmittedReview] = useState<SubmittedReview | null>(null);

  // Form states
  const [photo, setPhoto] = useState<File | null>(null);
  const [photoPreview, setPhotoPreview] = useState<string>('');
  const [reviewText, setReviewText] = useState('');
  const [rating, setRating] = useState(5);
  const [customerName, setCustomerName] = useState('');

  useEffect(() => {
    if (params.token) {
      fetchReviewData(params.token as string);
    }
  }, [params.token]);

  const fetchReviewData = async (token: string) => {
    try {
      const response = await fetch(`https://vanyadmin.progesio.my.id/api/vny/reviews/${token}`);
      const data = await response.json();

      if (response.ok) {
        // Check if review already submitted
        if (data.message === 'Review sudah pernah disubmit' && data.review) {
          setIsReviewSubmitted(true);
          setSubmittedReview(data.review);
        } else {
          setReviewData(data);
        }
      } else {
        setError(data.error || 'Review token tidak valid');
      }
    } catch (err) {
      setError('Terjadi kesalahan saat memuat data');
    } finally {
      setLoading(false);
    }
  };

  const handlePhotoChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      setPhoto(file);
      const reader = new FileReader();
      reader.onloadend = () => {
        setPhotoPreview(reader.result as string);
      };
      reader.readAsDataURL(file);
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    if (!photo || !reviewText.trim() || !customerName.trim()) {
      setError('Mohon lengkapi nama, upload foto dan tulis review Anda');
      return;
    }

    setSubmitting(true);
    setError('');

    try {
      const formData = new FormData();
      formData.append('photo', photo);
      formData.append('review_text', reviewText);
      formData.append('rating', rating.toString());
      formData.append('customer_name', customerName);

      const response = await fetch(`https://vanyadmin.progesio.my.id/api/vny/reviews/${params.token}/submit`, {
        method: 'POST',
        body: formData,
      });

      const data = await response.json();

      if (response.ok) {
        setSuccess(true);
      } else {
        // Handle validation errors
        if (data.messages && typeof data.messages === 'object') {
          const errorMessages = Object.values(data.messages).flat().join(', ');
          setError(errorMessages as string);
        } else {
          setError(data.error || 'Terjadi kesalahan saat mengirim review');
        }
      }
    } catch (err) {
      setError('Terjadi kesalahan saat mengirim review');
    } finally {
      setSubmitting(false);
    }
  };

  if (loading) {
    return (
      <div className="min-h-screen bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-red-600 mx-auto mb-4"></div>
          <p className="text-red-600 font-medium">Memuat data review...</p>
        </div>
      </div>
    );
  }

  if (error && !reviewData) {
    return (
      <div className="min-h-screen bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center">
        <div className="bg-white p-8 rounded-lg shadow-lg max-w-md mx-auto text-center">
          <div className="text-red-600 text-6xl mb-4">⚠️</div>
          <h2 className="text-2xl font-bold text-gray-800 mb-4">Oops!</h2>
          <p className="text-gray-600 mb-6">{error}</p>
          <button
            onClick={() => router.push('/')}
            className="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors"
          >
            Kembali ke Beranda
          </button>
        </div>
      </div>
    );
  }

  if (success) {
    return (
      <div className="min-h-screen bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center">
        <div className="bg-white p-8 rounded-lg shadow-lg max-w-md mx-auto text-center">
          <div className="text-green-600 text-6xl mb-4">✅</div>
          <h2 className="text-2xl font-bold text-gray-800 mb-4">Terima Kasih!</h2>
          <p className="text-gray-600 mb-6">Review Anda telah berhasil dikirim dan akan ditampilkan setelah disetujui admin.</p>
          <button
            onClick={() => router.push('/')}
            className="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors"
          >
            Kembali ke Beranda
          </button>
        </div>
      </div>
    );
  }

  // Display submitted review
  if (isReviewSubmitted && submittedReview) {
    const reviewDate = new Date(submittedReview.created_at);
    const renderStars = (rating: number) => {
      return Array.from({ length: 5 }, (_, i) => (
        <span 
          key={i} 
          className={`text-2xl ${i < rating ? 'text-yellow-500' : 'text-gray-300'}`}
          style={{
            textShadow: i < rating ? '0 0 6px rgba(255, 193, 7, 0.5)' : 'none',
            filter: i < rating ? 'brightness(1.2) saturate(1.3)' : 'none'
          }}
        >
          ★
        </span>
      ));
    };

    return (
      <div className="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100 py-12">
        <div className="container mx-auto px-4 max-w-4xl">
          {/* Header */}
          <div className="text-center mb-12">
            <h1 className="text-4xl font-bold text-red-600 mb-4">VNY STORE</h1>
            <h2 className="text-2xl font-semibold text-gray-800 mb-2">Review Anda</h2>
            <p className="text-gray-600">Terima kasih telah memberikan review untuk pesanan Anda!</p>
          </div>

          {/* Submitted Review Card */}
          <div className="bg-white rounded-lg shadow-lg overflow-hidden">
            <div className="bg-gradient-to-r from-red-600 to-red-700 text-white p-6">
              <div className="flex items-center justify-between">
                <div>
                  <h3 className="text-xl font-semibold">{submittedReview.customer_name}</h3>
                  <p className="opacity-90">Order #{submittedReview.order_number || 'N/A'}</p>
                </div>
                <div className="text-right">
                  <div className="text-sm opacity-90">Tanggal Review</div>
                  <div className="font-semibold">
                    {reviewDate.toLocaleDateString('id-ID', {
                      day: 'numeric',
                      month: 'long',
                      year: 'numeric'
                    })}
                  </div>
                </div>
              </div>
            </div>

            <div className="p-6">
              <div className="grid md:grid-cols-2 gap-8">
                {/* Photo Section */}
                <div>
                  <h4 className="text-lg font-semibold mb-4 text-gray-800">Foto Review</h4>
                  <div className="relative w-full h-64 bg-gray-100 rounded-lg overflow-hidden">
                    <SafeImage
                      src={submittedReview.photo_url ? `https://vanyadmin.progesio.my.id/storage/${submittedReview.photo_url}` : ''}
                      alt={`Review by ${submittedReview.customer_name}` || 'Review image'}
                      fill
                      className="object-cover"
                      fallbackContent={
                        <div className="flex items-center justify-center h-full text-gray-500">
                          <div className="text-center">
                            <div className="text-4xl mb-2">📷</div>
                            <p>Tidak ada foto</p>
                          </div>
                        </div>
                      }
                    />
                  </div>
                </div>

                {/* Review Content */}
                <div>
                  <div className="mb-6">
                    <h4 className="text-lg font-semibold mb-3 text-gray-800">Rating</h4>
                    <div className="flex items-center gap-2">
                      {renderStars(submittedReview.rating)}
                      <span className="text-lg font-semibold text-gray-700 ml-2">
                        {submittedReview.rating}/5
                      </span>
                    </div>
                  </div>

                  <div>
                    <h4 className="text-lg font-semibold mb-3 text-gray-800">Review</h4>
                    <div className="bg-gray-50 p-4 rounded-lg">
                      <p className="text-gray-700 leading-relaxed">
                        {submittedReview.review_text}
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              {/* Status Badge */}
              <div className="mt-8 text-center">
                <div className="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-full">
                  <div className="w-2 h-2 bg-blue-600 rounded-full mr-2"></div>
                  <span className="font-medium">Review telah diterima dan sedang dalam proses persetujuan</span>
                </div>
              </div>

              {/* Action Button */}
              <div className="mt-8 text-center">
                <button
                  onClick={() => router.push('/')}
                  className="bg-red-600 text-white px-8 py-3 rounded-lg hover:bg-red-700 transition-colors font-semibold"
                >
                  Kembali ke Beranda
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-red-50 to-red-100 py-12">
      <div className="container mx-auto px-4 max-w-4xl">
        {/* Header */}
        <div className="text-center mb-12">
          <h1 className="text-4xl font-bold text-red-600 mb-4">VNY STORE</h1>
          <h2 className="text-2xl font-semibold text-gray-900 mb-2">Review Pembelian Anda</h2>
          <p className="text-gray-700">Bagikan pengalaman Anda dengan sepatu keren dari VNY Store!</p>
        </div>

        {/* Order Info */}
        {reviewData && (
          <div className="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h3 className="text-xl font-semibold mb-4 text-gray-900">Detail Pesanan</h3>
            <div className="grid md:grid-cols-2 gap-4">
              <div>
                <p className="text-gray-800"><span className="font-medium text-gray-900">Nomor Pesanan:</span> {reviewData.order?.order_number || 'N/A'}</p>
                <p className="text-gray-800"><span className="font-medium text-gray-900">Pelanggan:</span> {reviewData.customer_name || 'N/A'}</p>
                <p className="text-gray-800"><span className="font-medium text-gray-900">Total:</span> Rp {reviewData.order?.total_amount?.toLocaleString() || '0'}</p>
              </div>
              <div>
                <p className="font-medium mb-2 text-gray-900">Barang:</p>
                {reviewData.order_items?.map((item, index) => (
                  <p key={index} className="text-sm text-gray-700">
                    {item.product?.name || 'Produk'} x {item.quantity || 0}
                  </p>
                ))}
              </div>
            </div>
          </div>
        )}

        {/* Review Form */}
        <div className="bg-white rounded-lg shadow-lg p-8">
          <form onSubmit={handleSubmit} className="space-y-6">
            {/* Photo Upload */}
            <div>
              <label className="block text-lg font-semibold text-gray-900 mb-4">
                Upload Foto Anda Berpose Keren! 📸
              </label>
              <div className="border-2 border-dashed border-red-300 rounded-lg p-6 text-center">
                <div className="relative w-64 h-64 mx-auto mb-4">
                  <SafeImage
                    src={photoPreview || ''}
                    alt="Preview"
                    fill
                    className="object-cover rounded-lg"
                    fallbackContent={
                      <div className="w-full h-full bg-gray-100 rounded-lg flex items-center justify-center">
                        <p className="text-gray-600">Preview foto akan muncul di sini</p>
                      </div>
                    }
                  />
                </div>
                <input
                  type="file"
                  accept="image/*"
                  onChange={handlePhotoChange}
                  className="hidden"
                  id="photo"
                  required
                />
                <label
                  htmlFor="photo"
                  className="bg-red-600 text-white px-6 py-3 rounded-lg cursor-pointer hover:bg-red-700 transition-colors inline-block"
                >
                  {photo ? 'Ganti Foto' : 'Pilih Foto'}
                </label>
                <p className="text-sm text-gray-600 mt-2">
                  Upload foto Anda yang sedang berpose keren dengan sepatu VNY Store!
                </p>
              </div>
            </div>

            {/* Customer Name */}
            <div>
              <label className="block text-lg font-semibold text-gray-900 mb-4">
                Nama Anda 👤
              </label>
              <input
                type="text"
                value={customerName}
                onChange={(e) => setCustomerName(e.target.value)}
                className="w-full border border-gray-300 rounded-lg p-4 focus:ring-2 focus:ring-red-600 focus:border-transparent text-gray-900 placeholder-gray-500"
                placeholder="Masukkan nama lengkap Anda..."
                required
                maxLength={100}
              />
              <p className="text-sm text-gray-600 mt-2">
                Nama akan ditampilkan bersama review Anda
              </p>
            </div>

            {/* Rating */}
            <div>
              <label className="block text-lg font-semibold text-gray-900 mb-4">
                Rating Produk ⭐
              </label>
              <div className="flex gap-1">
                {[1, 2, 3, 4, 5].map((star) => (
                  <button
                    key={star}
                    type="button"
                    onClick={() => setRating(star)}
                    className={`relative text-4xl transition-all duration-200 transform hover:scale-110 focus:outline-none ${
                      star <= rating 
                        ? 'text-yellow-500 drop-shadow-lg' 
                        : 'text-gray-300 hover:text-yellow-400'
                    }`}
                    style={{
                      textShadow: star <= rating ? '0 0 8px rgba(255, 193, 7, 0.6)' : 'none',
                      filter: star <= rating ? 'brightness(1.2) saturate(1.3)' : 'none'
                    }}
                  >
                    ★
                  </button>
                ))}
              </div>
              <p className="text-sm text-gray-600 mt-2">
                Pilih {rating} dari 5 bintang
              </p>
            </div>

            {/* Review Text */}
            <div>
              <label className="block text-lg font-semibold text-gray-900 mb-4">
                Ceritakan Pengalaman Anda! ✍️
              </label>
              <textarea
                value={reviewText}
                onChange={(e) => setReviewText(e.target.value)}
                rows={6}
                className="w-full border border-gray-300 rounded-lg p-4 focus:ring-2 focus:ring-red-600 focus:border-transparent text-gray-900 placeholder-gray-500"
                placeholder="Bagaimana pengalaman Anda dengan sepatu dari VNY Store? Ceritakan di sini..."
                required
                minLength={10}
                maxLength={1000}
              />
              <div className="text-right text-sm text-gray-600 mt-2">
                {reviewText.length}/1000 karakter
              </div>
            </div>

            {error && (
              <div className="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg">
                {error}
              </div>
            )}

            {/* Submit Button */}
            <div className="text-center">
              <button
                type="submit"
                disabled={submitting}
                className={`px-8 py-4 rounded-lg text-white font-semibold text-lg transition-colors ${
                  submitting
                    ? 'bg-gray-400 cursor-not-allowed'
                    : 'bg-red-600 hover:bg-red-700'
                }`}
              >
                {submitting ? 'Mengirim Review...' : 'Kirim Review 🚀'}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}