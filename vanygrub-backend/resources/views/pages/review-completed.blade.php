@extends('layouts.app')

@section('title', 'Review Sudah Selesai - VANY GROUP')
@section('description', 'Terima kasih, review Anda sudah diterima sebelumnya.')

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

.completed-container {
    max-width: 600px;
    margin: 50px auto;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    padding: 50px 40px;
    text-align: center;
    border: 2px solid rgba(139, 0, 0, 0.3);
}

.completed-icon {
    font-size: 80px;
    margin-bottom: 20px;
    color: #059669;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}

.completed-title {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    font-weight: 700;
    background: linear-gradient(45deg, #059669, #34D399);
    background-size: 200% 200%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 15px;
}

.completed-message {
    font-size: 16px;
    color: #666;
    margin-bottom: 30px;
    line-height: 1.6;
}

.review-summary {
    background: rgba(5, 150, 105, 0.1);
    border: 1px solid rgba(5, 150, 105, 0.2);
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
    text-align: left;
}

.review-summary h4 {
    color: #059669;
    margin-bottom: 15px;
    font-weight: 600;
    text-align: center;
}

.review-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    font-size: 14px;
}

.review-item .label {
    color: #666;
    font-weight: 500;
}

.review-item .value {
    color: #333;
    font-weight: 600;
}

.stars {
    color: #FFD700;
    font-size: 18px;
}

.review-text-preview {
    background: #f9f9f9;
    border-radius: 8px;
    padding: 15px;
    margin-top: 15px;
    text-align: left;
    font-style: italic;
    color: #555;
    border-left: 4px solid #059669;
}

.btn-group {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    display: inline-block;
    padding: 16px 32px;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    font-family: 'Poppins', sans-serif;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
    min-width: 180px;
}

.btn-primary {
    background: linear-gradient(135deg, #8B0000, #CD853F);
    color: white;
    box-shadow: 0 5px 15px rgba(139, 0, 0, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(139, 0, 0, 0.4);
    text-decoration: none;
    color: white;
}

.btn-secondary {
    background: rgba(139, 0, 0, 0.1);
    color: #8B0000;
    border: 2px solid #8B0000;
}

.btn-secondary:hover {
    background: #8B0000;
    color: white;
    transform: translateY(-2px);
    text-decoration: none;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    body {
        padding: 10px 5px;
    }
    
    .completed-container {
        padding: 35px 25px;
        margin: 20px auto;
    }
    
    .completed-title {
        font-size: 24px;
    }
    
    .completed-icon {
        font-size: 60px;
    }
    
    .btn-group {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 100%;
        max-width: 280px;
    }

    .review-item {
        flex-direction: column;
        gap: 5px;
    }
}

@media (max-width: 480px) {
    .completed-container {
        padding: 25px 20px;
        margin: 10px auto;
        border-radius: 15px;
    }
    
    .completed-title {
        font-size: 20px;
    }
    
    .completed-icon {
        font-size: 50px;
    }
}
</style>

<div class="completed-container">
    <div class="completed-icon">✅</div>
    <h1 class="completed-title">Review Sudah Selesai!</h1>
    <p class="completed-message">
        Terima kasih! Review Anda sudah berhasil diterima sebelumnya. 
        Kami sangat menghargai feedback yang telah Anda berikan.
    </p>

    <div class="review-summary">
        <h4>📝 Ringkasan Review Anda</h4>
        
        <div class="review-item">
            <span class="label">Nama Customer:</span>
            <span class="value">{{ $existingReview->customer_name ?: 'Anonymous' }}</span>
        </div>
        
        <div class="review-item">
            <span class="label">Rating:</span>
            <span class="value">
                <span class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        {{ $i <= ($existingReview->rating ?: 0) ? '⭐' : '☆' }}
                    @endfor
                </span>
                ({{ $existingReview->rating ?: 0 }}/5)
            </span>
        </div>
        
        <div class="review-item">
            <span class="label">Tanggal Review:</span>
            <span class="value">{{ $existingReview->created_at ? $existingReview->created_at->format('d M Y H:i') : '-' }}</span>
        </div>
        
        <div class="review-item">
            <span class="label">Status:</span>
            <span class="value">{{ $existingReview->is_approved ? '✅ Disetujui' : '⏳ Menunggu Persetujuan' }}</span>
        </div>

        @if($existingReview->review_text)
        <div class="review-text-preview">
            <strong>Review Anda:</strong><br>
            "{{ Str::limit($existingReview->review_text, 200) }}"
        </div>
        @endif
    </div>

    <div class="btn-group">
        <a href="https://vanygroup.id/vny" class="btn btn-primary">
            🛍️ Kembali Belanja
        </a>
        <a href="https://vanygroup.id" class="btn btn-secondary">
            🏠 Beranda
        </a>
    </div>

    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
        <p style="color: #999; font-size: 12px; margin: 0;">
            Review Token: {{ $token }}
        </p>
    </div>
</div>

@endsection