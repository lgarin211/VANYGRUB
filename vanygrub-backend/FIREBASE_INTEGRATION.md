# Firebase Integration for VNY Store

Firebase telah berhasil diintegrasikan ke dalam project VANYGRUB dengan konfigurasi lengkap untuk Authentication, Firestore, dan Storage.

## 📁 File Structure

```
resources/js/
├── firebase.js              # Konfigurasi utama Firebase
├── firebase-auth.js         # Utilities untuk Authentication
├── firebase-firestore.js    # Utilities untuk Firestore Database
├── firebase-storage.js      # Utilities untuk Storage
├── vny-auth.js             # Component UI untuk Authentication
└── app.js                  # Import Firebase ke aplikasi utama
```

## 🔥 Firebase Services yang Tersedia

### 1. Authentication
- **Login dengan Email/Password**
- **Registrasi User Baru**
- **Google Sign-In**
- **Password Reset**
- **Auth State Management**

### 2. Firestore Database
- **Orders Management** - Kelola pesanan pelanggan
- **Wishlist** - Simpan produk favorit
- **Reviews** - Ulasan produk
- **Generic CRUD Operations**

### 3. Storage
- **Product Images** - Upload gambar produk
- **User Avatars** - Upload foto profil
- **Review Images** - Upload gambar review

## 🚀 Cara Penggunaan

### Authentication

```javascript
import { signInUser, createUser, signInWithGoogle } from './firebase-auth.js';

// Login
const result = await signInUser(email, password);
if (result.success) {
    console.log('User logged in:', result.user);
}

// Register
const result = await createUser(email, password, displayName);

// Google Sign-In
const result = await signInWithGoogle();
```

### Firestore

```javascript
import { addDocument, getCollection, queryDocuments } from './firebase-firestore.js';

// Add order
const orderData = {
    userId: 'user123',
    items: [...],
    total: 500000,
    shippingAddress: '...'
};
await addOrder(orderData);

// Get user orders
const orders = await getUserOrders('user123');
```

### Storage

```javascript
import { uploadProductImage, uploadUserAvatar } from './firebase-storage.js';

// Upload product image
const result = await uploadProductImage(file, productId, 0, (progress) => {
    console.log(`Upload progress: ${progress}%`);
});

if (result.success) {
    console.log('Image uploaded:', result.url);
}
```

## 🎨 UI Components

### Auth Modal
Component `VNYAuth` menyediakan modal untuk login/register yang siap digunakan:

```javascript
// Show login modal
VNYAuth.showModal();

// Sign out user
VNYAuth.signOut();
```

### Integration dengan HTML
Tambahkan data attributes untuk integrasi otomatis:

```html
<!-- Login Button -->
<button data-auth="login" onclick="VNYAuth.showModal()">
    Masuk
</button>

<!-- User Menu (hidden by default) -->
<div data-auth="user-menu" style="display: none;">
    <img data-auth="user-avatar" src="" alt="Avatar">
    <span data-auth="user-name"></span>
    <button onclick="VNYAuth.signOut()">Keluar</button>
</div>
```

## 🔧 Environment Variables

Firebase configuration menggunakan environment variables di `.env`:

```env
VITE_FIREBASE_API_KEY=AIzaSyArg9iQEsm0BNHf5jn8ujbNsvVfqit2nN8
VITE_FIREBASE_AUTH_DOMAIN=vanygroup.firebaseapp.com
VITE_FIREBASE_PROJECT_ID=vanygroup
VITE_FIREBASE_STORAGE_BUCKET=vanygroup.firebasestorage.app
VITE_FIREBASE_MESSAGING_SENDER_ID=355023468464
VITE_FIREBASE_APP_ID=1:355023468464:web:99d10040c7b2c98f810875
VITE_FIREBASE_MEASUREMENT_ID=G-GT22MNMCPP
```

## 📊 Firebase Collections Structure

### orders
```javascript
{
    id: "auto-generated",
    userId: "user-id",
    orderNumber: "VNY-1234567890",
    items: [
        {
            productId: "product-id",
            name: "Product Name",
            price: 500000,
            quantity: 2,
            size: "42",
            color: "Black"
        }
    ],
    total: 1000000,
    status: "pending", // pending, processing, shipped, delivered, cancelled
    shippingAddress: "...",
    paymentMethod: "...",
    createdAt: Timestamp,
    updatedAt: Timestamp
}
```

### wishlists
```javascript
{
    id: "auto-generated",
    userId: "user-id",
    productId: "product-id",
    addedAt: Timestamp
}
```

### reviews
```javascript
{
    id: "auto-generated",
    productId: "product-id",
    userId: "user-id",
    userName: "User Name",
    rating: 5,
    title: "Review Title",
    comment: "Review comment",
    images: ["url1", "url2"],
    helpful: 0,
    createdAt: Timestamp
}
```

## 🏗️ Build & Deploy

Setelah instalasi Firebase, pastikan untuk build ulang assets:

```bash
npm run build
```

Untuk development:
```bash
npm run dev
```

## 🔒 Security Rules

Pastikan untuk mengatur Firebase Security Rules di Firebase Console:

### Firestore Rules
```javascript
rules_version = '2';
service cloud.firestore {
  match /databases/{database}/documents {
    // Orders - only user can access their own orders
    match /orders/{orderId} {
      allow read, write: if request.auth != null && request.auth.uid == resource.data.userId;
    }
    
    // Wishlists - only user can access their own wishlist
    match /wishlists/{wishlistId} {
      allow read, write: if request.auth != null && request.auth.uid == resource.data.userId;
    }
    
    // Reviews - authenticated users can read all, only own writes
    match /reviews/{reviewId} {
      allow read: if true;
      allow write: if request.auth != null && request.auth.uid == resource.data.userId;
    }
  }
}
```

### Storage Rules
```javascript
rules_version = '2';
service firebase.storage {
  match /b/{bucket}/o {
    // User avatars
    match /users/{userId}/{allPaths=**} {
      allow read, write: if request.auth != null && request.auth.uid == userId;
    }
    
    // Product images (admin only for write, public read)
    match /products/{productId}/{allPaths=**} {
      allow read: if true;
      // Add admin check here
    }
    
    // Review images
    match /reviews/{reviewId}/{allPaths=**} {
      allow read: if true;
      allow write: if request.auth != null;
    }
  }
}
```

## 📱 Next Steps

1. **Implement auth UI** di halaman product detail
2. **Add order management** di user dashboard
3. **Integrate wishlist** functionality
4. **Add review system** untuk produk
5. **Setup push notifications** untuk order status

---

Firebase integration siap digunakan! 🚀
