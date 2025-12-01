# Gallery Upload Service untuk Next.js

Untuk mengintegrasikan dengan Next.js, buat service file berikut:

## 1. Gallery Service (lib/galleryService.js)

```javascript
const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api';

class GalleryService {
  constructor() {
    this.baseURL = `${API_BASE_URL}/gallery`;
  }

  // Get authorization headers
  getHeaders(includeAuth = false) {
    const headers = {
      'Accept': 'application/json',
    };

    if (includeAuth) {
      const token = localStorage.getItem('auth_token');
      if (token) {
        headers['Authorization'] = `Bearer ${token}`;
      }
    }

    return headers;
  }

  // Get all gallery items
  async getGalleryItems(params = {}) {
    const queryParams = new URLSearchParams(params).toString();
    const url = queryParams ? `${this.baseURL}?${queryParams}` : this.baseURL;
    
    try {
      const response = await fetch(url, {
        headers: this.getHeaders(),
      });
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      return await response.json();
    } catch (error) {
      console.error('Error fetching gallery items:', error);
      throw error;
    }
  }

  // Get single gallery item
  async getGalleryItem(id) {
    try {
      const response = await fetch(`${this.baseURL}/${id}`, {
        headers: this.getHeaders(),
      });
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      return await response.json();
    } catch (error) {
      console.error('Error fetching gallery item:', error);
      throw error;
    }
  }

  // Upload single file
  async uploadFile(file, metadata = {}) {
    const formData = new FormData();
    formData.append('file', file);
    
    // Add metadata
    Object.keys(metadata).forEach(key => {
      if (metadata[key] !== null && metadata[key] !== undefined) {
        formData.append(key, metadata[key]);
      }
    });

    try {
      const response = await fetch(this.baseURL, {
        method: 'POST',
        headers: this.getHeaders(true),
        body: formData,
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
      }

      return await response.json();
    } catch (error) {
      console.error('Error uploading file:', error);
      throw error;
    }
  }

  // Upload multiple files
  async uploadMultipleFiles(files, metadata = {}) {
    const formData = new FormData();
    
    files.forEach((file, index) => {
      formData.append(`files[]`, file);
    });

    // Add metadata
    Object.keys(metadata).forEach(key => {
      if (metadata[key] !== null && metadata[key] !== undefined) {
        formData.append(key, metadata[key]);
      }
    });

    try {
      const response = await fetch(`${this.baseURL}/bulk-upload`, {
        method: 'POST',
        headers: this.getHeaders(true),
        body: formData,
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
      }

      return await response.json();
    } catch (error) {
      console.error('Error uploading multiple files:', error);
      throw error;
    }
  }

  // Update gallery item
  async updateGalleryItem(id, data) {
    try {
      const response = await fetch(`${this.baseURL}/${id}`, {
        method: 'PUT',
        headers: {
          ...this.getHeaders(true),
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
      }

      return await response.json();
    } catch (error) {
      console.error('Error updating gallery item:', error);
      throw error;
    }
  }

  // Delete gallery item
  async deleteGalleryItem(id) {
    try {
      const response = await fetch(`${this.baseURL}/${id}`, {
        method: 'DELETE',
        headers: this.getHeaders(true),
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
      }

      return await response.json();
    } catch (error) {
      console.error('Error deleting gallery item:', error);
      throw error;
    }
  }

  // Helper method to validate file before upload
  validateFile(file, type = null) {
    const maxSize = 50 * 1024 * 1024; // 50MB
    const allowedTypes = {
      image: ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'],
      video: ['video/mp4', 'video/avi', 'video/mov', 'video/webm'],
      audio: ['audio/mp3', 'audio/wav', 'audio/ogg', 'audio/m4a'],
      document: ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']
    };

    // Check file size
    if (file.size > maxSize) {
      return { valid: false, error: 'File size exceeds 50MB limit' };
    }

    // Check file type if specified
    if (type && allowedTypes[type]) {
      if (!allowedTypes[type].includes(file.type)) {
        return { valid: false, error: `Invalid ${type} file type` };
      }
    } else {
      // Check if file type is allowed in any category
      const allAllowedTypes = Object.values(allowedTypes).flat();
      if (!allAllowedTypes.includes(file.type)) {
        return { valid: false, error: 'File type not supported' };
      }
    }

    return { valid: true };
  }

  // Get file type from mime type
  getFileType(file) {
    if (file.type.startsWith('image/')) return 'image';
    if (file.type.startsWith('video/')) return 'video';
    if (file.type.startsWith('audio/')) return 'audio';
    return 'document';
  }
}

export default new GalleryService();
```

## 2. React Hook untuk Gallery (hooks/useGallery.js)

```javascript
import { useState, useEffect } from 'react';
import galleryService from '../lib/galleryService';

export function useGallery(params = {}) {
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [pagination, setPagination] = useState(null);

  useEffect(() => {
    fetchItems();
  }, [JSON.stringify(params)]);

  const fetchItems = async () => {
    setLoading(true);
    setError(null);
    
    try {
      const response = await galleryService.getGalleryItems(params);
      setItems(response.data.data);
      setPagination({
        current_page: response.data.current_page,
        last_page: response.data.last_page,
        per_page: response.data.per_page,
        total: response.data.total,
      });
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const uploadFile = async (file, metadata = {}) => {
    setError(null);
    
    try {
      const validation = galleryService.validateFile(file);
      if (!validation.valid) {
        throw new Error(validation.error);
      }

      const response = await galleryService.uploadFile(file, metadata);
      
      // Refresh the list after upload
      await fetchItems();
      
      return response.data;
    } catch (err) {
      setError(err.message);
      throw err;
    }
  };

  const uploadMultipleFiles = async (files, metadata = {}) => {
    setError(null);
    
    try {
      // Validate all files
      for (let file of files) {
        const validation = galleryService.validateFile(file);
        if (!validation.valid) {
          throw new Error(`${file.name}: ${validation.error}`);
        }
      }

      const response = await galleryService.uploadMultipleFiles(files, metadata);
      
      // Refresh the list after upload
      await fetchItems();
      
      return response.data;
    } catch (err) {
      setError(err.message);
      throw err;
    }
  };

  const deleteItem = async (id) => {
    setError(null);
    
    try {
      await galleryService.deleteGalleryItem(id);
      
      // Remove item from local state
      setItems(prev => prev.filter(item => item.id !== id));
      
      return true;
    } catch (err) {
      setError(err.message);
      throw err;
    }
  };

  const updateItem = async (id, data) => {
    setError(null);
    
    try {
      const response = await galleryService.updateGalleryItem(id, data);
      
      // Update item in local state
      setItems(prev => prev.map(item => 
        item.id === id ? response.data : item
      ));
      
      return response.data;
    } catch (err) {
      setError(err.message);
      throw err;
    }
  };

  return {
    items,
    loading,
    error,
    pagination,
    uploadFile,
    uploadMultipleFiles,
    deleteItem,
    updateItem,
    refresh: fetchItems
  };
}
```

## 3. Usage Example

```javascript
// pages/admin/gallery.js
import { useState } from 'react';
import { useGallery } from '../hooks/useGallery';

export default function GalleryPage() {
  const [uploadProgress, setUploadProgress] = useState(0);
  const { items, loading, error, uploadFile } = useGallery();

  const handleFileUpload = async (event) => {
    const files = Array.from(event.target.files);
    
    for (let file of files) {
      try {
        const metadata = {
          category: 'products',
          title: file.name,
          description: 'Uploaded via admin panel'
        };
        
        await uploadFile(file, metadata);
        console.log('File uploaded successfully');
      } catch (error) {
        console.error('Upload failed:', error);
      }
    }
  };

  return (
    <div>
      <h1>Gallery Management</h1>
      
      <input 
        type="file" 
        multiple 
        onChange={handleFileUpload}
        accept="image/*,video/*,.pdf,.doc,.docx"
      />
      
      {loading && <p>Loading...</p>}
      {error && <p>Error: {error}</p>}
      
      <div className="gallery-grid">
        {items.map(item => (
          <div key={item.id} className="gallery-item">
            <img src={item.file_url} alt={item.title} />
            <h3>{item.title}</h3>
            <p>{item.file_size_human}</p>
          </div>
        ))}
      </div>
    </div>
  );
}
```

## 4. Installation & Setup

1. **Install dependencies di Laravel:**
```bash
composer install
```

2. **Run migrations:**
```bash
php artisan migrate
```

3. **Create storage link:**
```bash
php artisan storage:link
```

4. **Set up routes di Laravel (routes/api.php):**
```php
require base_path('routes/gallery.php');
```

5. **Set environment variables:**
```env
GALLERY_MAX_FILE_SIZE=52428800
GALLERY_GENERATE_THUMBNAILS=true
GALLERY_STORAGE_DISK=public
```