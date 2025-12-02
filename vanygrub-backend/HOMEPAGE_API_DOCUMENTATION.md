# Homepage API Documentation

## Base URL
```
/api/vny/homepage/
```

## Endpoints

### 1. Get Homepage Constants
**GET** `/api/vny/homepage/constants`

Mendapatkan semua konstanta untuk homepage termasuk metadata, hero section, gallery items, categories, colors, dan animation settings.

**Response:**
```json
{
    "status": "success",
    "message": "Homepage constants retrieved successfully",
    "data": {
        "META": {
            "TITLE": "VANY GROUB - Premium Lifestyle Collection",
            "DESCRIPTION": "Discover premium lifestyle products from traditional fashion to modern hospitality services",
            "KEYWORDS": ["vany", "premium", "lifestyle", "fashion", "hospitality", "beauty"]
        },
        "HERO_SECTION": {
            "TITLE": "Welcome to VANY GROUB",
            "SUBTITLE": "Premium Lifestyle Collection",
            "DESCRIPTION": "Explore our curated collection of premium products and services"
        },
        "GALLERY_ITEMS": [
            {
                "id": 1,
                "title": "Vany Songket",
                "image": "https://images.unsplash.com/photo-1546548970-71785318a17b?w=600&h=800&fit=crop",
                "description": "Koleksi songket tradisional dengan desain modern...",
                "target": "/",
                "category": "Traditional Fashion"
            }
        ],
        "CATEGORIES": [
            "Traditional Fashion",
            "Footwear",
            "Hospitality",
            "Real Estate",
            "Beauty & Wellness",
            "Culinary",
            "Travel",
            "Health & Fitness",
            "Home & Living"
        ],
        "COLORS": {
            "PRIMARY": "#800000",
            "SECONDARY": "#000000", 
            "ACCENT": "#ffffff",
            "GRADIENT": "linear-gradient(135deg, #800000 0%, #000000 100%)"
        },
        "ANIMATION": {
            "CAROUSEL_INTERVAL": 5000,
            "TRANSITION_DURATION": 300
        }
    }
}
```

### 2. Get Gallery Item by ID
**GET** `/api/vny/homepage/gallery/{id}`

Mendapatkan satu item gallery berdasarkan ID.

**Parameters:**
- `id` (integer): ID gallery item

**Response:**
```json
{
    "status": "success",
    "message": "Gallery item retrieved successfully", 
    "data": {
        "id": 1,
        "title": "Vany Songket",
        "image": "https://images.unsplash.com/photo-1546548970-71785318a17b?w=600&h=800&fit=crop",
        "description": "Koleksi songket tradisional dengan desain modern...",
        "target": "/",
        "category": "Traditional Fashion"
    }
}
```

**Error Response (404):**
```json
{
    "status": "error",
    "message": "Gallery item not found"
}
```

### 3. Get Gallery Items by Category
**GET** `/api/vny/homepage/category/{category}`

Mendapatkan semua gallery items berdasarkan kategori.

**Parameters:**
- `category` (string): Nama kategori (case-insensitive)

**Response:**
```json
{
    "status": "success",
    "message": "Gallery items by category retrieved successfully",
    "data": [
        {
            "id": 1,
            "title": "Vany Songket",
            "image": "https://images.unsplash.com/photo-1546548970-71785318a17b?w=600&h=800&fit=crop",
            "description": "Koleksi songket tradisional dengan desain modern...",
            "target": "/",
            "category": "Traditional Fashion"
        }
    ],
    "count": 1
}
```

### 4. Get Site Configuration
**GET** `/api/vny/homepage/site-config`

Mendapatkan konfigurasi situs termasuk informasi kontak, social media, dan navigasi.

**Response:**
```json
{
    "status": "success",
    "message": "Site configuration retrieved successfully",
    "data": {
        "site_name": "VANY GROUB",
        "tagline": "Premium Lifestyle Collection",
        "description": "Your one-stop destination for premium lifestyle products and services",
        "contact": {
            "email": "info@VANY GROUB.com",
            "phone": "+62 812-3456-7890",
            "address": "Jl. Premium No. 123, Jakarta, Indonesia"
        },
        "social_media": {
            "facebook": "https://facebook.com/VANY GROUB",
            "instagram": "https://instagram.com/VANY GROUB",
            "twitter": "https://twitter.com/VANY GROUB",
            "youtube": "https://youtube.com/VANY GROUB"
        },
        "navigation": {
            "main_menu": [
                {"label": "Home", "url": "/", "active": true},
                {"label": "VNY Products", "url": "/vny", "active": false}
            ],
            "footer_links": {
                "services": [
                    {"label": "Traditional Fashion", "url": "/services/fashion"}
                ],
                "company": [
                    {"label": "About Us", "url": "/about"}
                ]
            }
        }
    }
}
```

## Configuration

### Environment Variables
Anda dapat mengkustomisasi konstanta melalui file `.env`:

```env
# Meta Information
HOMEPAGE_TITLE="VANY GROUB - Premium Lifestyle Collection"
HOMEPAGE_DESCRIPTION="Discover premium lifestyle products from traditional fashion to modern hospitality services"

# Hero Section
HERO_TITLE="Welcome to VANY GROUB"
HERO_SUBTITLE="Premium Lifestyle Collection" 
HERO_DESCRIPTION="Explore our curated collection of premium products and services"

# Brand Colors
BRAND_PRIMARY_COLOR="#800000"
BRAND_SECONDARY_COLOR="#000000"
BRAND_ACCENT_COLOR="#ffffff"
BRAND_GRADIENT="linear-gradient(135deg, #800000 0%, #000000 100%)"

# Animation Settings
CAROUSEL_INTERVAL=5000
TRANSITION_DURATION=300

# Site Configuration
SITE_NAME="VANY GROUB"
SITE_TAGLINE="Premium Lifestyle Collection"
SITE_DESCRIPTION="Your one-stop destination for premium lifestyle products and services"

# Contact Information
CONTACT_EMAIL="info@VANY GROUB.com"
CONTACT_PHONE="+62 812-3456-7890"
CONTACT_ADDRESS="Jl. Premium No. 123, Jakarta, Indonesia"

# Social Media
SOCIAL_FACEBOOK="https://facebook.com/VANY GROUB"
SOCIAL_INSTAGRAM="https://instagram.com/VANY GROUB"
SOCIAL_TWITTER="https://twitter.com/VANY GROUB"
SOCIAL_YOUTUBE="https://youtube.com/VANY GROUB"
```

### Database Structure
Tabel `homepage_gallery_items`:
- `id`: Primary key
- `title`: Judul item
- `image`: URL gambar
- `description`: Deskripsi item
- `target`: Link tujuan
- `category`: Kategori item
- `is_active`: Status aktif (boolean)
- `sort_order`: Urutan tampil
- `created_at`, `updated_at`: Timestamps

### Fallback System
API ini menggunakan sistem fallback:
1. Jika ada data di database, gunakan data dari database
2. Jika database kosong, gunakan data hardcoded sebagai fallback
3. Konfigurasi dari `config/homepage.php` akan digunakan untuk meta, colors, dll.

## Usage Example

### Frontend Integration (Next.js)
```typescript
// lib/api.ts
export async function getHomepageConstants() {
  const response = await fetch('/api/vny/homepage/constants');
  return response.json();
}

// pages/index.tsx
import { useEffect, useState } from 'react';
import { getHomepageConstants } from '../lib/api';

export default function Homepage() {
  const [constants, setConstants] = useState(null);

  useEffect(() => {
    getHomepageConstants().then(data => {
      if (data.status === 'success') {
        setConstants(data.data);
      }
    });
  }, []);

  if (!constants) return <div>Loading...</div>;

  return (
    <div>
      <h1>{constants.HERO_SECTION.TITLE}</h1>
      <p>{constants.HERO_SECTION.DESCRIPTION}</p>
      {/* Gallery items rendering */}
    </div>
  );
}
```