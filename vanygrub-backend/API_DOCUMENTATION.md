# VANY GROUB API Documentation

## Base URL
```
http://127.0.0.1:8000/api/vny
```

## API Endpoints

### 1. Data Endpoints (For Next.js Constants)

#### Get All Data
**GET** `/data`

Returns all data in format suitable for Next.js constants including categories, products, hero sections.

**Response Example:**
```json
{
  "categories": [...],
  "products": [...],
  "heroSections": [...],
  "homeData": {...}
}
```

#### Get Home Data
**GET** `/home-data`

Returns data specifically for home page including hero sections, categories, and featured products.

**Response Example:**
```json
{
  "heroSections": [...],
  "categories": [...],
  "featuredProducts": [...]
}
```

### 2. Categories API

#### Get All Categories
**GET** `/categories`

Returns all active categories with product count.

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Sneakers",
      "slug": "sneakers",
      "description": "Category for Sneakers",
      "image": null,
      "isActive": true,
      "sortOrder": 1,
      "productsCount": 9
    }
  ],
  "message": "Categories retrieved successfully"
}
```

#### Get Single Category
**GET** `/categories/{id}`

Returns single category with its products.

#### Create Category
**POST** `/categories`

**Request Body:**
```json
{
  "name": "New Category",
  "slug": "new-category",
  "description": "Category description",
  "image": "path/to/image.jpg",
  "is_active": true,
  "sort_order": 1
}
```

#### Update Category
**PUT** `/categories/{id}`

#### Delete Category
**DELETE** `/categories/{id}`

### 3. Products API

#### Get All Products
**GET** `/products`

**Query Parameters:**
- `category_id` - Filter by category
- `featured` - Filter featured products (true/false)
- `search` - Search in name, description

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Air Jordan 1 Retro",
      "slug": "air-jordan-1-retro",
      "description": "Desak singgal sepatu",
      "detailedDescription": "...",
      "shortDescription": null,
      "price": 5000000,
      "salePrice": 4500000,
      "sku": "VNY-0001",
      "stockQuantity": 80,
      "manageStock": true,
      "inStock": true,
      "status": "active",
      "image": "/temp/nike-just-do-it(6).jpg",
      "mainImage": "/temp/nike-just-do-it(6).jpg",
      "gallery": [...],
      "weight": "450.00",
      "dimensions": null,
      "categoryId": 1,
      "category": {
        "id": 1,
        "name": "Sneakers",
        "slug": "sneakers"
      },
      "serialNumber": "Limited",
      "colors": [...],
      "sizes": [...],
      "countryOrigin": "Vietnam",
      "warranty": "1 Tahun",
      "releaseDate": "2024-01-15",
      "isFeatured": true,
      "createdAt": "2025-11-29T...",
      "updatedAt": "2025-11-29T..."
    }
  ],
  "message": "Products retrieved successfully"
}
```

#### Get Single Product
**GET** `/products/{id}`

#### Get Product by Slug
**GET** `/products/slug/{slug}`

#### Get Featured Products
**GET** `/featured-products`

Returns only products where `isFeatured = true`.

#### Create Product
**POST** `/products`

**Request Body:**
```json
{
  "name": "Product Name",
  "slug": "product-slug",
  "description": "Product description",
  "price": 1000000,
  "category_id": 1,
  "image": "path/to/image.jpg",
  "stock_quantity": 10,
  "sku": "VNY-XXXX"
}
```

#### Update Product
**PUT** `/products/{id}`

#### Delete Product
**DELETE** `/products/{id}`

### 4. Hero Sections API

#### Get All Hero Sections
**GET** `/hero-sections`

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "VNY",
      "subtitle": "Premium Collection",
      "description": "Discover the ultimate blend of style and comfort",
      "image": "/product/air-force-1-07-wb-shoes-j46FxX.png",
      "bgColor": "#dc2626",
      "textColor": "#ffffff",
      "buttonText": "Shop Now",
      "price": "$299",
      "isActive": true,
      "sortOrder": 1,
      "createdAt": "2025-11-29T...",
      "updatedAt": "2025-11-29T..."
    }
  ],
  "message": "Hero sections retrieved successfully"
}
```

#### Get Single Hero Section
**GET** `/hero-sections/{id}`

#### Create Hero Section
**POST** `/hero-sections`

**Request Body:**
```json
{
  "title": "Hero Title",
  "subtitle": "Hero Subtitle",
  "description": "Hero description",
  "image": "path/to/image.jpg",
  "bg_color": "#dc2626",
  "text_color": "#ffffff",
  "button_text": "Click Me",
  "price": "$299",
  "is_active": true,
  "sort_order": 1
}
```

#### Update Hero Section
**PUT** `/hero-sections/{id}`

#### Delete Hero Section
**DELETE** `/hero-sections/{id}`

## Data Format Notes

### Product Data Format
- All prices are in Indonesian Rupiah (IDR) as integers
- `salePrice` can be null if no sale price
- `gallery` is an array of image paths
- `colors` and `sizes` are arrays
- `category` includes basic category info (id, name, slug)
- Boolean fields use camelCase (`isActive`, `isFeatured`, `inStock`, `manageStock`)

### Category Data Format
- `productsCount` is automatically calculated
- Boolean fields use camelCase (`isActive`)

### Hero Section Data Format
- Color fields (`bgColor`, `textColor`) store hex color codes
- Boolean fields use camelCase (`isActive`)

## Error Responses

All endpoints return standard error responses:

```json
{
  "message": "Error message",
  "errors": {
    "field": ["Validation error message"]
  }
}
```

## Authentication

Currently, API endpoints are publicly accessible. For admin operations, consider implementing authentication middleware.

## CORS

CORS is configured to allow all origins for development. Update `config/cors.php` for production use.

## Example Usage in Next.js

```javascript
// Fetch all data for constants
const response = await fetch('http://127.0.0.1:8000/api/vny/data');
const { categories, products, heroSections } = await response.json();

// Fetch featured products
const featuredResponse = await fetch('http://127.0.0.1:8000/api/vny/featured-products');
const { data: featuredProducts } = await featuredResponse.json();

// Search products
const searchResponse = await fetch('http://127.0.0.1:8000/api/vny/products?search=nike');
const { data: searchResults } = await searchResponse.json();
```
