# VANY GROUB Media Upload API Documentation

## Overview
API untuk upload dan manajemen media (gambar, video, dokumen) yang dapat digunakan oleh frontend Next.js untuk mengelola gallery dan asset media.

## Base URL
```
https://vanygroup.id/api/vny/media
```

## Endpoints

### 1. Upload Single Media
**POST** `/upload`

Upload satu file media ke server.

**Request:**
- Method: `POST`
- Content-Type: `multipart/form-data`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `file` | File | Yes | File yang akan diupload (max 10MB) |
| `type` | String | Yes | Tipe media: `image`, `video`, `document` |
| `folder` | String | No | Nama folder penyimpanan (default: `general`) |

**Response Success (200):**
```json
{
  "success": true,
  "message": "File uploaded successfully",
  "data": {
    "id": 1,
    "filename": "sample-image-1638360000.jpg",
    "original_name": "sample-image.jpg",
    "path": "media/image/gallery/sample-image-1638360000.jpg",
    "url": "https://vanygroup.id/storage/media/image/gallery/sample-image-1638360000.jpg",
    "size": 245760,
    "mime_type": "image/jpeg",
    "type": "image",
    "folder": "gallery",
    "formatted_size": "240.00 KB"
  }
}
```

**Response Error (422):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "file": ["The file field is required."],
    "type": ["The selected type is invalid."]
  }
}
```

### 2. Upload Multiple Media
**POST** `/upload-multiple`

Upload beberapa file media sekaligus.

**Request:**
- Method: `POST`
- Content-Type: `multipart/form-data`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `files[]` | File[] | Yes | Array file yang akan diupload (max 10 files, 10MB per file) |
| `type` | String | Yes | Tipe media: `image`, `video`, `document` |
| `folder` | String | No | Nama folder penyimpanan (default: `general`) |

**Response Success (200):**
```json
{
  "success": true,
  "message": "3 files uploaded successfully",
  "data": [
    {
      "id": 1,
      "filename": "image1-1638360000.jpg",
      "original_name": "image1.jpg",
      "path": "media/image/gallery/image1-1638360000.jpg",
      "url": "https://vanygroup.id/storage/media/image/gallery/image1-1638360000.jpg",
      "size": 245760,
      "mime_type": "image/jpeg",
      "type": "image",
      "folder": "gallery",
      "formatted_size": "240.00 KB"
    }
    // ... more files
  ]
}
```

### 3. Delete Media
**DELETE** `/delete`

Hapus file media dari server dan database.

**Request:**
- Method: `DELETE`
- Content-Type: `application/json`

**Body:**
```json
{
  "path": "media/image/gallery/sample-image-1638360000.jpg"
}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "File deleted successfully"
}
```

**Response Error (404):**
```json
{
  "success": false,
  "message": "File not found"
}
```

### 4. Get Media List
**GET** `/list`

Ambil daftar media files dengan pagination.

**Query Parameters:**
| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `type` | String | `image` | Tipe media: `image`, `video`, `document` |
| `folder` | String | `general` | Nama folder |
| `page` | Integer | `1` | Nomor halaman |
| `per_page` | Integer | `20` | Jumlah item per halaman (max 50) |

**Example Request:**
```
GET /list?type=image&folder=gallery&page=1&per_page=20
```

**Response Success (200):**
```json
{
  "success": true,
  "data": [
    {
      "filename": "sample-image-1638360000.jpg",
      "path": "media/image/gallery/sample-image-1638360000.jpg",
      "url": "https://vanygroup.id/storage/media/image/gallery/sample-image-1638360000.jpg",
      "size": 245760,
      "last_modified": 1638360000,
      "mime_type": "image/jpeg"
    }
    // ... more files
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 20,
    "total": 150,
    "total_pages": 8
  }
}
```

## File Type Restrictions

### Image Files
**Extensions:** jpg, jpeg, png, gif, webp, svg  
**MIME Types:** image/jpeg, image/png, image/gif, image/webp, image/svg+xml  
**Max Size:** 10MB

### Video Files
**Extensions:** mp4, avi, mov, wmv, flv, webm  
**MIME Types:** video/mp4, video/avi, video/quicktime, video/x-ms-wmv, video/x-flv, video/webm  
**Max Size:** 10MB

### Document Files
**Extensions:** pdf, doc, docx, xls, xlsx, ppt, pptx, txt  
**MIME Types:** application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, text/plain, etc.  
**Max Size:** 10MB

## Error Codes

| HTTP Code | Description |
|-----------|-------------|
| 200 | Success |
| 422 | Validation Error |
| 404 | File Not Found |
| 500 | Server Error |

## Usage Examples

### JavaScript/Fetch
```javascript
// Upload single image
const uploadImage = async (file) => {
  const formData = new FormData();
  formData.append('file', file);
  formData.append('type', 'image');
  formData.append('folder', 'gallery');

  const response = await fetch('https://vanygroup.id/api/vny/media/upload', {
    method: 'POST',
    body: formData
  });

  return await response.json();
};

// Upload multiple files
const uploadMultipleImages = async (files) => {
  const formData = new FormData();
  files.forEach(file => formData.append('files[]', file));
  formData.append('type', 'image');
  formData.append('folder', 'gallery');

  const response = await fetch('https://vanygroup.id/api/vny/media/upload-multiple', {
    method: 'POST',
    body: formData
  });

  return await response.json();
};
```

### cURL Examples
```bash
# Upload single file
curl -X POST https://vanygroup.id/api/vny/media/upload \
  -F "file=@/path/to/image.jpg" \
  -F "type=image" \
  -F "folder=gallery"

# Delete file
curl -X DELETE https://vanygroup.id/api/vny/media/delete \
  -H "Content-Type: application/json" \
  -d '{"path":"media/image/gallery/sample-image-1638360000.jpg"}'

# Get media list
curl "https://vanygroup.id/api/vny/media/list?type=image&folder=gallery&page=1&per_page=20"
```

## Admin Panel
Media files dapat dikelola melalui Filament admin panel di:
```
https://vanygroup.id/admin/media
```

Fitur admin panel:
- Preview gambar
- Filter berdasarkan tipe dan folder
- Copy URL media
- Delete files
- Bulk operations
- Search dan sort

## Folder Structure
Media files disimpan dalam struktur folder berikut:
```
storage/app/public/media/
├── image/
│   ├── gallery/
│   ├── products/
│   └── general/
├── video/
│   ├── promotional/
│   └── general/
└── document/
    ├── manuals/
    └── general/
```

## Security Notes
- File validation berdasarkan extension dan MIME type
- Maximum file size: 10MB per file
- Maximum 10 files per multiple upload
- Files disimpan di `storage/app/public` dengan symlink ke `public/storage`
- CORS configured untuk Next.js frontend
