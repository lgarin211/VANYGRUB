@extends('layouts.app')

@section('title', 'Link Review Tidak Valid - VANY GROUP')
@section('description', 'Link review yang Anda akses tidak valid atau sudah tidak berlaku.')

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

.error-container {
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

.error-icon {
    font-size: 80px;
    margin-bottom: 20px;
    color: #DC2626;
}

.error-title {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    font-weight: 700;
    color: #DC2626;
    margin-bottom: 15px;
}

.error-message {
    font-size: 16px;
    color: #666;
    margin-bottom: 30px;
    line-height: 1.6;
}

.error-details {
    background: rgba(220, 38, 38, 0.1);
    border: 1px solid rgba(220, 38, 38, 0.2);
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 30px;
}

.error-details h4 {
    color: #DC2626;
    margin-bottom: 10px;
    font-weight: 600;
}

.error-details p {
    color: #666;
    margin: 5px 0;
    font-size: 14px;
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
    
    .error-container {
        padding: 35px 25px;
        margin: 20px auto;
    }
    
    .error-title {
        font-size: 24px;
    }
    
    .error-icon {
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
}

@media (max-width: 480px) {
    .error-container {
        padding: 25px 20px;
        margin: 10px auto;
        border-radius: 15px;
    }
    
    .error-title {
        font-size: 20px;
    }
    
    .error-icon {
        font-size: 50px;
    }
}
</style>

<div class="error-container">
    <div class="error-icon">❌</div>
    <h1 class="error-title">Link Review Tidak Valid</h1>
    <p class="error-message">
        Maaf, link review yang Anda akses tidak valid atau sudah tidak berlaku. 
        Hal ini bisa terjadi karena beberapa alasan berikut:
    </p>

    <div class="error-details">
        <h4>Kemungkinan Penyebab:</h4>
        <p>• Link review belum dibuat atau tidak tersedia</p>
        <p>• Kode token salah atau sudah kedaluwarsa</p>
        <p>• Link mungkin sudah digunakan dan tidak berlaku lagi</p>
        <p>• Terjadi kesalahan dalam sistem</p>
    </div>

    <p style="color: #666; font-size: 14px; margin-bottom: 30px;">
        <strong>Token:</strong> {{ $token }}
    </p>

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
            Jika Anda merasa ini adalah kesalahan, silakan hubungi customer service kami.
        </p>
    </div>
</div>

@endsection