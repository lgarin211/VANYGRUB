# QR Batch Generator dengan Pilihan Ukuran Kertas

Generator QR batch telah selesai dibuat dengan fitur pemilihan ukuran kertas A4 dan A3. Berikut adalah cara menggunakannya:

## Fitur Utama

✅ **Batch QR Generation**: Generate multiple QR codes sekaligus  
✅ **Pilihan Ukuran Kertas**: A4 dan A3 dengan layout yang berbeda  
✅ **Print-Ready Layout**: Layout siap cetak dengan panduan potong  
✅ **Admin Interface**: Interface Filament yang mudah digunakan  
✅ **No PDF Dependencies**: Menggunakan HTML+SVG (tidak perlu imagick)  

## Layout Specifications

### A4 Layout (210mm x 297mm)
- **Grid**: 2 kolom x 2 baris = 4 QR codes per halaman
- **QR Size**: 70mm x 70mm
- **Margin**: 20mm semua sisi
- **Gap**: 5mm antar QR

### A3 Layout (297mm x 420mm) 
- **Grid**: 4 kolom x 2 baris = 8 QR codes per halaman
- **QR Size**: 70mm x 70mm  
- **Margin**: 20mm semua sisi
- **Gap**: 5mm antar QR

## Cara Penggunaan

### 1. Akses Admin Panel
```
URL: /admin/qr-batch-generator
```

### 2. Input Quantity
- Masukkan jumlah QR yang ingin dibuat (1-50)
- Klik "Generate QR Batch"

### 3. Pilih Ukuran Kertas
- Setelah generate, otomatis redirect ke halaman pilihan kertas
- **A4**: Cocok untuk printing rumahan (4 QR per halaman)
- **A3**: Cocok untuk printing massal (8 QR per halaman)

### 4. Preview & Print
- Review layout sebelum print
- Klik "Print QR Codes" untuk print langsung
- Atau gunakan Ctrl+P untuk print dialog

## File yang Dibuat

### Controllers
- `app/Http/Controllers/QrBatchController.php`
  - `selectPaper()`: Menampilkan halaman pilihan kertas
  - `preview()`: Menampilkan layout print dengan QR codes

### Views
- `resources/views/qr/paper-selection.blade.php`
  - Interface pemilihan A4 vs A3
  - Perbandingan visual layout
  
- `resources/views/qr/batch-preview.blade.php`
  - Layout print dengan CSS @page rules
  - Dynamic paper size support
  - Cutting guides dan brand text

### Filament Pages
- `app/Filament/Pages/QrBatchGenerator.php`
  - Admin interface untuk generate batch
  - Form validation dan token generation

## Routes

```php
// Paper selection page
Route::get('/qr/batch/paper-selection', [QrBatchController::class, 'selectPaper'])
    ->name('qr.batch.paper-selection');

// Print preview page  
Route::get('/qr/batch/preview', [QrBatchController::class, 'preview'])
    ->name('qr.batch.preview');
```

## Database

QR tokens disimpan di tabel `customer_reviews` dengan:
- `review_token`: Unique token untuk setiap QR
- `customer_name`: null (untuk batch QR)
- `customer_email`: null (untuk batch QR)
- Timestamps otomatis

## CSS Print Optimizations

### @page Rules
```css
@page {
    size: A4; /* atau A3 dynamically */
    margin: 0;
}
```

### Cutting Guides
- Dashed border untuk panduan potong
- Posisi corner marks untuk alignment
- Brand text "VANY GROUB" pada setiap QR

## Dependencies

- **SimpleSoftwareIO/simple-qrcode**: QR generation
- **Laravel Sessions**: Token storage
- **Filament 3.3**: Admin interface
- **HTML+SVG**: Print output (no PDF libs needed)

## Testing

Untuk test workflow lengkap:

1. Jalankan server: `php artisan serve`
2. Akses: `http://localhost:8000/admin/qr-batch-generator`
3. Login sebagai admin
4. Generate beberapa QR codes
5. Pilih ukuran kertas
6. Test print preview

## Production Notes

- QR codes mengarah ke: `/review/{token}`
- Tokens unik menggunakan `Str::random(10)`
- Layout responsive untuk berbagai printer
- Support browser print (Chrome/Edge recommended)

---

**Status: ✅ COMPLETED**

QR Batch Generator dengan pilihan ukuran kertas A4 dan A3 telah selesai dan siap digunakan!
