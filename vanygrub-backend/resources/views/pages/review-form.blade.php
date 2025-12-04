@extends('layouts.app')

@section('title', 'Tulis Review - VANY GROUP')
@section('description', 'Berikan review Anda untuk produk VANY GROUP. Pengalaman Anda sangat berharga bagi kami.')

@section('content')
<style>
/* Google Fonts */
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Poppins:wght@200;300;400;500;600;700&display=swap');

body {
    margin: 0;
    padding: 20px 10px;
    background: linear-gradient(135deg, #8B0000 0%, #B22222 25%, #CD853F 100%);
    background-attachment: fixed;
    font-family: 'Poppins', sans-serif;
    min-height: 100vh;
    overflow-x: hidden;
}

.review-container {
    max-width: 600px;
    margin: 0 auto;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    padding: 40px;
    border: 2px solid rgba(139, 0, 0, 0.3);
}

.review-header {
    text-align: center;
    margin-bottom: 30px;
}

.review-title {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    font-weight: 700;
    background: linear-gradient(45deg, #8B0000, #CD853F, #8B0000);
    background-size: 200% 200%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: gradientShift 3s ease-in-out infinite;
    margin-bottom: 10px;
}

.review-subtitle {
    font-size: 16px;
    color: #666;
    font-weight: 400;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.order-info {
    background: linear-gradient(135deg, rgba(139, 0, 0, 0.1), rgba(205, 133, 63, 0.1));
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 30px;
    border-left: 5px solid #8B0000;
}

.order-info h3 {
    font-family: 'Playfair Display', serif;
    color: #8B0000;
    margin: 0 0 10px 0;
    font-size: 18px;
}

.order-info p {
    margin: 5px 0;
    color: #333;
    font-size: 14px;
}

.form-group {
    margin-bottom: 25px;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #ddd;
    border-radius: 10px;
    font-size: 14px;
    font-family: 'Poppins', sans-serif;
    transition: all 0.3s ease;
    background: white !important;
    color: #333 !important;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: #8B0000;
    box-shadow: 0 0 0 3px rgba(139, 0, 0, 0.1);
    background: white !important;
    color: #333 !important;
}

.form-control::placeholder {
    color: #999 !important;
    opacity: 1;
}

textarea.form-control {
    min-height: 100px;
    resize: vertical;
    line-height: 1.5 !important;
    font-weight: 400 !important;
}

/* Star Rating */
.rating-container {
    display: flex;
    align-items: center;
    gap: 10px;
}

.star-rating {
    display: flex;
    gap: 5px;
}

.star {
    font-size: 28px;
    color: #ddd;
    cursor: pointer;
    transition: all 0.2s ease;
    user-select: none;
}

.star:hover,
.star.active {
    color: #FFD700;
    transform: scale(1.1);
}

.star.active {
    animation: starPulse 0.3s ease;
}

@keyframes starPulse {
    0% { transform: scale(1.1); }
    50% { transform: scale(1.3); }
    100% { transform: scale(1.1); }
}

.rating-text {
    font-size: 14px;
    color: #666;
    font-weight: 500;
    min-width: 100px;
}

/* Photo Upload */
.photo-upload {
    position: relative;
    border: 2px dashed #ddd;
    border-radius: 10px;
    padding: 30px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #fafafa;
}

.photo-upload:hover {
    border-color: #8B0000;
    background: rgba(139, 0, 0, 0.05);
}

.photo-upload.has-file {
    border-color: #8B0000;
    background: rgba(139, 0, 0, 0.05);
}

.photo-preview {
    max-width: 200px;
    max-height: 200px;
    border-radius: 10px;
    margin: 10px auto;
    display: none;
}

.upload-icon {
    font-size: 48px;
    color: #8B0000;
    margin-bottom: 10px;
}

.upload-text {
    color: #666;
    font-size: 14px;
}

.file-input {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

/* Submit Button */
.btn-submit {
    width: 100%;
    background: linear-gradient(135deg, #8B0000, #CD853F);
    color: white;
    border: none;
    padding: 16px 24px;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    font-family: 'Poppins', sans-serif;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(139, 0, 0, 0.3);
}

.btn-submit:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Success/Error Messages */
.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-weight: 500;
}

.alert-success {
    background: rgba(34, 197, 94, 0.1);
    color: #059669;
    border: 1px solid rgba(34, 197, 94, 0.2);
}

.alert-error {
    background: rgba(239, 68, 68, 0.1);
    color: #DC2626;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

/* Mobile Responsive */
@media (max-width: 768px) {
    body {
        padding: 10px 5px;
    }

    .review-container {
        padding: 25px;
        margin: 10px;
    }

    .review-title {
        font-size: 24px;
    }

    .star {
        font-size: 24px;
    }
}

@media (max-width: 480px) {
    .review-container {
        padding: 20px;
        margin: 5px;
        border-radius: 15px;
    }

    .review-title {
        font-size: 20px;
    }

    .star {
        font-size: 20px;
    }

    .rating-container {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
}
</style>

<div class="review-container">
    <div class="review-header">
        <h1 class="review-title">Tulis Review Anda</h1>
        <p class="review-subtitle">Bagikan pengalaman Anda dengan produk VANY GROUP</p>
    </div>

    @if($existingReview)
        <div class="alert alert-success">
            <strong>Review sudah diberikan!</strong><br>
            Terima kasih, Anda sudah memberikan review untuk order ini pada {{ $existingReview->created_at->format('d M Y H:i') }}.
        </div>
    @else
        <div class="order-info">
            <h3>Informasi Review</h3>
            <p><strong>Kode Review:</strong> {{ $token }}</p>
            <p><strong>Tanggal:</strong> {{ now()->format('d M Y H:i') }}</p>
            @if(is_object($order) && isset($order->id))
                <p><strong>Status:</strong> Order ditemukan di sistem</p>
            @else
                <p><strong>Terimakasih Sudah Membeli Produk kami</strong></p>
            @endif
        </div>

        <form id="reviewForm" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="customer_name" class="form-label">Nama Anda *</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name"
                       placeholder="Masukkan nama Anda" required>
            </div>

            <div class="form-group">
                <label for="photo" class="form-label">Foto Anda (Opsional)</label>
                <div class="photo-upload" onclick="document.getElementById('photo').click()">
                    <div class="upload-content">
                        <div class="upload-icon">📷</div>
                        <div class="upload-text">Klik untuk upload foto Anda<br><small>Format: JPG, PNG (Max: 2MB)</small></div>
                    </div>
                    <img id="photoPreview" class="photo-preview" alt="Preview">
                    <input type="file" class="file-input" id="photo" name="photo" accept="image/*">
                </div>
            </div>

            <div class="form-group">
                <label for="review_text" class="form-label">Review Anda *</label>
                <textarea class="form-control" id="review_text" name="review_text"
                          placeholder="Ceritakan pengalaman Anda dengan produk VANY GROUP..." required></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Rating *</label>
                <div class="rating-container">
                    <div class="star-rating">
                        <span class="star" data-rating="1">⭐</span>
                        <span class="star" data-rating="2">⭐</span>
                        <span class="star" data-rating="3">⭐</span>
                        <span class="star" data-rating="4">⭐</span>
                        <span class="star" data-rating="5">⭐</span>
                    </div>
                    <span class="rating-text">Pilih rating</span>
                </div>
                <input type="hidden" id="rating" name="rating" required>
            </div>

            <button type="submit" class="btn-submit">
                <span class="btn-text">Kirim Review</span>
            </button>
        </form>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating');
    const ratingText = document.querySelector('.rating-text');
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photoPreview');
    const photoUpload = document.querySelector('.photo-upload');
    const reviewForm = document.getElementById('reviewForm');

    const ratingTexts = {
        1: 'Sangat Buruk',
        2: 'Buruk',
        3: 'Cukup',
        4: 'Baik',
        5: 'Sangat Baik'
    };

    // Star rating functionality
    stars.forEach((star, index) => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.dataset.rating);
            ratingInput.value = rating;
            ratingText.textContent = ratingTexts[rating];

            // Update star display
            stars.forEach((s, i) => {
                if (i < rating) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
        });

        star.addEventListener('mouseenter', function() {
            const rating = parseInt(this.dataset.rating);
            stars.forEach((s, i) => {
                if (i < rating) {
                    s.style.color = '#FFD700';
                } else {
                    s.style.color = '#ddd';
                }
            });
        });
    });

    document.querySelector('.star-rating').addEventListener('mouseleave', function() {
        const currentRating = parseInt(ratingInput.value) || 0;
        stars.forEach((s, i) => {
            if (i < currentRating) {
                s.style.color = '#FFD700';
            } else {
                s.style.color = '#ddd';
            }
        });
    });

    // Photo upload preview
    photoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.src = e.target.result;
                photoPreview.style.display = 'block';
                photoUpload.classList.add('has-file');
                photoUpload.querySelector('.upload-content').style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });

    // Form submission
    reviewForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('.btn-submit');
        const btnText = submitBtn.querySelector('.btn-text');
        const originalText = btnText.textContent;

        // Validation
        if (!ratingInput.value) {
            alert('Silakan pilih rating terlebih dahulu!');
            return;
        }

        // Disable button and show loading
        submitBtn.disabled = true;
        btnText.textContent = 'Mengirim...';

        const formData = new FormData(this);

        fetch('{{ route("review.store", $token) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                const successAlert = document.createElement('div');
                successAlert.className = 'alert alert-success';
                successAlert.innerHTML = '<strong>Berhasil!</strong><br>' + data.message;

                reviewForm.parentNode.insertBefore(successAlert, reviewForm);
                reviewForm.style.display = 'none';

                // Scroll to top
                window.scrollTo({top: 0, behavior: 'smooth'});
            } else {
                // Show error message
                alert('Error: ' + (data.message || 'Terjadi kesalahan'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
        })
        .finally(() => {
            // Re-enable button
            submitBtn.disabled = false;
            btnText.textContent = originalText;
        });
    });
});
</script>

@endsection
