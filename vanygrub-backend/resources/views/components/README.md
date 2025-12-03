# VNY Navbar Component

Komponen navbar yang dapat digunakan kembali untuk website VNY Store.

## Lokasi File
`resources/views/components/vny-navbar.blade.php`

## Cara Penggunaan

### Penggunaan Dasar
```blade
@include('components.vny-navbar')
```

### Penggunaan dengan Parameter
```blade
@include('components.vny-navbar', [
    'currentPage' => 'product',
    'showSearch' => true,
    'cartCount' => 3,
    'transparent' => false
])
```

## Parameter yang Tersedia

| Parameter | Type | Default | Deskripsi |
|-----------|------|---------|-----------|
| `currentPage` | string | 'home' | Halaman aktif saat ini ('home', 'product', 'about', 'gallery') |
| `showSearch` | boolean | true | Menampilkan atau menyembunyikan tombol search |
| `cartCount` | integer | 0 | Jumlah item dalam keranjang (badge merah) |
| `transparent` | boolean | false | Membuat background navbar transparan |

## Contoh Implementasi

### Di halaman Product List
```blade
@include('components.vny-navbar', ['currentPage' => 'product'])
```

### Di halaman Gallery/Store
```blade
@include('components.vny-navbar', ['currentPage' => 'gallery'])
```

### Di halaman Product Detail
```blade
@include('components.vny-navbar', ['currentPage' => 'product'])
```

## File yang Sudah Menggunakan Komponen

1. `resources/views/pages/vny-product-list.blade.php`
2. `resources/views/pages/vny-store.blade.php`  
3. `resources/views/pages/vny-product-detail.blade.php`

## Fitur Komponen

- **Responsive Design**: Navbar menyesuaikan dengan ukuran layar
- **Active State**: Menandai menu yang sedang aktif
- **Cart Badge**: Menampilkan jumlah item di keranjang
- **Hover Effects**: Animasi saat hover
- **Flexible**: Dapat dikustomisasi dengan props
- **Konsisten**: Tampilan yang seragam di semua halaman

## Modifikasi

Untuk mengubah tampilan atau fungsi navbar, edit file:
`resources/views/components/vny-navbar.blade.php`

Semua halaman yang menggunakan komponen ini akan otomatis ter-update.
