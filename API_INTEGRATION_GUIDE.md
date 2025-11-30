# VanyGrub API Integration Guide

## Overview
VanyGrub frontend sekarang menggunakan Laravel backend API untuk mengambil data, menggantikan penggunaan JSON files lokal. Ini memberikan data yang real-time dan kemampuan untuk mengelola konten melalui admin panel.

## Architecture Changes

### Before (JSON-based)
```
Frontend (Next.js) → constants/dataHome.json → Components
                  → constants/productsData.json
```

### After (API-based) 
```
Frontend (Next.js) → Laravel API → Database (MySQL) → Components
                  ↘ Fallback to local JSON if API fails
```

## Key Files Modified

### 1. API Client (`lib/api.ts`)
- Centralized API client with error handling
- Automatic retries and fallback mechanisms
- Data transformation utilities

### 2. Custom Hooks (`hooks/useApi.ts`)
- `useHomeData()` - Home page data with fallback
- `useProducts()` - Products with filtering
- `useProduct(id)` - Single product detail
- `useFeaturedProducts()` - Featured products only
- `useCategories()` - Product categories

### 3. Components Updated
- ✅ `HeroSection.tsx` - Uses API with loading states
- ✅ `OurCollection.tsx` - Dynamic product collection
- ✅ `ProductList.tsx` - Real-time product filtering
- ✅ `ProductGrid.tsx` - API-driven product grid
- ✅ `SpecialOffer.tsx` - Live special offers
- ✅ `app/product/[id]/page.tsx` - Dynamic product details

### 4. Environment Configuration
```env
# .env.local
NEXT_PUBLIC_API_URL=http://127.0.0.1:8000 /api/vny
```

## Available API Endpoints

| Endpoint | Purpose | Frontend Usage |
|----------|---------|----------------|
| `/api/vny/data` | All data for constants | `useHomeData()` |
| `/api/vny/home-data` | Home page specific | Home components |
| `/api/vny/products` | Products with filters | `useProducts()` |
| `/api/vny/featured-products` | Featured products only | `useFeaturedProducts()` |
| `/api/vny/categories` | Product categories | `useCategories()` |
| `/api/vny/hero-sections` | Hero carousel data | `HeroSection` |

## Features

### ✅ Fallback Mechanism
- If API fails, automatically falls back to local JSON files
- Seamless user experience even during backend maintenance
- No breaking changes for existing functionality

### ✅ Loading States
- Proper loading indicators while fetching data
- Skeleton screens for better UX
- Error handling with retry options

### ✅ Real-time Data
- Content can be updated through admin panel
- No need to redeploy frontend for content changes
- Dynamic pricing and inventory updates

### ✅ Performance Optimizations
- Client-side caching with React hooks
- Efficient re-renders only when data changes
- Minimal API calls with smart dependency arrays

## Usage Examples

### Basic Data Fetching
```tsx
import { useHomeData } from '../hooks/useApi';

const MyComponent = () => {
  const { data, loading, error } = useHomeData();
  
  if (loading) return <div>Loading...</div>;
  if (error) return <div>Error: {error}</div>;
  
  return <div>{data.heroSection.slides[0].title}</div>;
};
```

### Product Filtering
```tsx
import { useProducts } from '../hooks/useApi';

const ProductList = () => {
  const { products, loading } = useProducts({
    category_id: 1,
    featured: true,
    search: 'nike'
  });
  
  return (
    <div>
      {products.map(product => (
        <div key={product.id}>{product.name}</div>
      ))}
    </div>
  );
};
```

## Development Commands

### Start Development Servers
```bash
# Backend (Laravel)
cd vanygrub-backend
php artisan serve

# Frontend (Next.js)
cd ..
npm run dev
```

### Test API Integration
```bash
node test-api-integration.js
```

### Admin Panel Access
- URL: `http://127.0.0.1:8000 /admin`
- Email: `admin@vanygrub.com`
- Password: `password123`

## Deployment Considerations

### Environment Variables
```env
# Production
NEXT_PUBLIC_API_URL=https://your-api-domain.com/api/vny

# Development
NEXT_PUBLIC_API_URL=http://127.0.0.1:8000 /api/vny
```

### Backend Requirements
- Laravel backend must be running and accessible
- Database properly migrated and seeded
- CORS configured for frontend domain

### Fallback Strategy
- Local JSON files kept as backup
- Automatic fallback if API unreachable
- Graceful degradation of functionality

## Benefits

1. **Dynamic Content** - Content manageable through admin panel
2. **Real-time Updates** - No frontend deployment needed for content changes
3. **Better Performance** - Database queries optimized vs static JSON
4. **Scalability** - Easy to add new endpoints and features
5. **SEO Friendly** - Server-side data fetching possible
6. **Reliability** - Fallback mechanism ensures site always works

## Next Steps

1. **Caching Strategy** - Implement Redis caching for better performance
2. **Image Optimization** - CDN integration for product images
3. **Search Enhancement** - Elasticsearch integration for better search
4. **Real-time Features** - WebSocket for live inventory updates
5. **Mobile API** - Separate mobile-optimized endpoints

---

🎉 **VanyGrub API integration is now complete and fully functional!**