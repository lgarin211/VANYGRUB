# Panduan Deployment ke Hosting Biasa

## Persiapan Sebelum Upload

### 1. Build Assets Production
```bash
npm run build
```

### 2. Struktur File yang Harus Di-Upload
Upload semua file dan folder berikut ke hosting:

```
├── app/
├── bootstrap/
├── config/
├── database/
├── public/          # <- ROOT FOLDER di hosting (document root)
│   ├── build/       # <- File hasil npm run build (WAJIB upload)
│   ├── css/
│   ├── js/
│   ├── .htaccess
│   └── index.php
├── resources/
├── routes/
├── storage/         # <- Set permission 755 atau 777
├── vendor/          # <- Upload hasil composer install
├── .env             # <- Buat file .env baru di hosting
└── artisan
```

## Setup di Hosting

### 1. Document Root
- Set document root hosting ke folder `public/`
- Jika tidak bisa set document root, pindahkan isi folder `public/` ke root hosting

### 2. File .env
Buat file `.env` baru di hosting dengan konfigurasi production:

```env
APP_NAME="VanyGrub"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

CACHE_DRIVER=file
FILESYSTEM_DISK=local
LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

### 3. Generate App Key
Jalankan via terminal hosting atau file PHP:
```bash
php artisan key:generate
```

### 4. Migrasi Database
```bash
php artisan migrate --force
```

### 5. Set Permission
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

## Troubleshooting

### Jika Error "Vite manifest not found"
- Pastikan folder `public/build/` dan `public/build/manifest.json` sudah ter-upload
- Jalankan `npm run build` di local sebelum upload

### Jika Error 500
- Cek file `.env` sudah benar
- Cek permission folder `storage/` dan `bootstrap/cache/`
- Cek log error di `storage/logs/laravel.log`

### Jika CSS/JS tidak load
- Pastikan path dalam `.env` variable `APP_URL` sudah benar
- Cek apakah file di `public/build/assets/` ter-upload dengan benar

## Checklist Deployment

- [ ] `npm run build` sudah dijalankan
- [ ] Folder `public/build/` ter-upload lengkap
- [ ] File `.env` sudah dibuat dan dikonfigurasi
- [ ] Document root sudah di-set ke folder `public/`
- [ ] Database sudah dibuat dan dikonfigurasi
- [ ] Migration sudah dijalankan
- [ ] Permission folder `storage/` sudah di-set
- [ ] App key sudah di-generate