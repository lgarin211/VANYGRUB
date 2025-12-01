'use client';

import { useState, useEffect } from 'react';
import { useParams, useRouter } from 'next/navigation';
import Image from 'next/image';

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

interface ReviewData {
  review_token: string;
  order?: Order;
  customer_name?: string;
  order_items?: OrderItem[];
}

export default function ReviewForm() {
  const params = useParams();
  const router = useRouter();
  const [reviewData, setReviewData] = useState<ReviewData | null>(null);
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState(false);

  // Form states
  const [photo, setPhoto] = useState<File | null>(null);
  const [photoPreview, setPhotoPreview] = useState<string>('');
  const [reviewText, setReviewText] = useState('');
  const [rating, setRating] = useState(5);

  useEffect(() => {
    if (params.token) {
      fetchReviewData(params.token as string);
    }
  }, [params.token]);

  const fetchReviewData = async (token: string) => {
    try {
      const response = await fetch(`http://localhost:8000/api/vny/reviews/${token}`);
      const data = await response.json();
      
      if (response.ok) {
        setReviewData(data);
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
    
    if (!photo || !reviewText.trim()) {
      setError('Mohon upload foto dan tulis review Anda');
      return;
    }

    setSubmitting(true);
    setError('');

    try {
      const formData = new FormData();
      formData.append('photo', photo);
      formData.append('review_text', reviewText);
      formData.append('rating', rating.toString());

      const response = await fetch(`http://localhost:8000/api/vny/reviews/${params.token}/submit`, {
        method: 'POST',
        body: formData,
      });

      const data = await response.json();

      if (response.ok) {
        setSuccess(true);
      } else {
        setError(data.error || 'Terjadi kesalahan saat mengirim review');
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

  return (
    <div className="min-h-screen bg-gradient-to-br from-red-50 to-red-100 py-12">
      <div className="container mx-auto px-4 max-w-4xl">
        {/* Header */}
        <div className="text-center mb-12">
          <h1 className="text-4xl font-bold text-red-600 mb-4">VNY STORE</h1>
          <h2 className="text-2xl font-semibold text-gray-800 mb-2">Review Your Purchase</h2>
          <p className="text-gray-600">Bagikan pengalaman Anda dengan sepatu keren dari VNY Store!</p>
        </div>

        {/* Order Info */}
        {reviewData && (
          <div className="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h3 className="text-xl font-semibold mb-4">Detail Pesanan</h3>
            <div className="grid md:grid-cols-2 gap-4">
              <div>
                <p><span className="font-medium">Order Number:</span> {reviewData.order?.order_number || 'N/A'}</p>
                <p><span className="font-medium">Customer:</span> {reviewData.customer_name || 'N/A'}</p>
                <p><span className="font-medium">Total:</span> Rp {reviewData.order?.total_amount?.toLocaleString() || '0'}</p>
              </div>
              <div>
                <p className="font-medium mb-2">Items:</p>
                {reviewData.order_items?.map((item, index) => (
                  <p key={index} className="text-sm text-gray-600">
                    {item.product?.name || 'Product'} x {item.quantity || 0}
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
              <label className="block text-lg font-semibold text-gray-800 mb-4">
                Upload Foto Anda Berpose Keren! 📸
              </label>
              <div className="border-2 border-dashed border-red-300 rounded-lg p-6 text-center">
                {photoPreview ? (
                  <div className="relative w-64 h-64 mx-auto mb-4">
                    <Image 
                      src={photoPreview} 
                      alt="Preview"
                      fill
                      className="object-cover rounded-lg"
                    />
                  </div>
                ) : (
                  <div className="w-64 h-64 mx-auto mb-4 bg-gray-100 rounded-lg flex items-center justify-center">
                    <p className="text-gray-500">Preview foto akan muncul di sini</p>
                  </div>
                )}
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
                <p className="text-sm text-gray-500 mt-2">
                  Upload foto Anda yang sedang berpose keren dengan sepatu VNY Store!
                </p>
              </div>
            </div>

            {/* Rating */}
            <div>
              <label className="block text-lg font-semibold text-gray-800 mb-4">
                Rating Produk ⭐
              </label>
              <div className="flex gap-2">
                {[1, 2, 3, 4, 5].map((star) => (
                  <button
                    key={star}
                    type="button"
                    onClick={() => setRating(star)}
                    className={`text-3xl ${star <= rating ? 'text-yellow-400' : 'text-gray-300'} hover:text-yellow-400 transition-colors`}
                  >
                    ⭐
                  </button>
                ))}
              </div>
            </div>

            {/* Review Text */}
            <div>
              <label className="block text-lg font-semibold text-gray-800 mb-4">
                Ceritakan Pengalaman Anda! ✍️
              </label>
              <textarea
                value={reviewText}
                onChange={(e) => setReviewText(e.target.value)}
                rows={6}
                className="w-full border border-gray-300 rounded-lg p-4 focus:ring-2 focus:ring-red-600 focus:border-transparent"
                placeholder="Bagaimana pengalaman Anda dengan sepatu dari VNY Store? Ceritakan di sini..."
                required
                minLength={10}
                maxLength={1000}
              />
              <div className="text-right text-sm text-gray-500 mt-2">
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