<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\Category;
use App\Models\HeroSection;
use App\Http\Controllers\Api\DataController;

class FrontendController extends Controller
{
    protected $dataController;

    public function __construct()
    {
        $this->dataController = new DataController();
    }

    /**
     * Homepage
     */
    public function home()
    {
        $apiData = $this->dataController->getAllData();
        $data = json_decode($apiData->getContent(), true);

        return view('pages.home', compact('data'));
    }

    /**
     * VNY Store Page
     */
    public function vnyStore()
    {
        $apiData = $this->dataController->getAllData();
        $data = json_decode($apiData->getContent(), true);

        return view('pages.vny-store', compact('data'));
    }

    /**
     * Gallery Page
     */
    public function gallery()
    {
        $categories = Category::where('is_active', true)->get();
        $products = Product::where('status', 'active')->get();

        return view('pages.gallery', compact('categories', 'products'));
    }

    /**
     * About Page
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Transactions Page
     */
    public function transactions()
    {
        return view('pages.transactions');
    }

    /**
     * Product List Page (VNY Products)
     */
    public function productList(Request $request)
    {
        // Get filter parameters
        $selectedCategory = $request->get('category', 'all');
        $searchQuery = $request->get('search', '');
        $priceRange = $request->get('price_range', 'all');
        $sortBy = $request->get('sort', 'default');

        // Fetch categories from specific API endpoint
        $categories = collect();
        try {
            $categoriesResponse = \Http::timeout(15)->get('https://vanygroup.id/api/vny/categories');

            if ($categoriesResponse->successful()) {
                $categoriesData = $categoriesResponse->json();
                if (isset($categoriesData['data']) && is_array($categoriesData['data'])) {
                    $categories = collect($categoriesData['data']);
                } elseif (is_array($categoriesData)) {
                    $categories = collect($categoriesData);
                }
                \Log::info('Categories API successful', ['count' => $categories->count()]);
            } else {
                \Log::warning('Categories API failed', ['status' => $categoriesResponse->status()]);
            }
        } catch (\Exception $e) {
            \Log::error('Categories API error: ' . $e->getMessage());
        }

        // Fallback categories if API fails
        if ($categories->isEmpty()) {
            $categories = collect([
                ['id' => 1, 'name' => 'Slip On', 'description' => 'Sepatu slip on casual'],
                ['id' => 2, 'name' => 'Sneakers', 'description' => 'Sepatu sneakers modern'],
                ['id' => 3, 'name' => 'Low Top', 'description' => 'Sepatu low top stylish']
            ]);
        }

        // Fetch products from specific API endpoint
        $products = collect();
        try {
            $productsResponse = \Http::timeout(15)->get('https://vanygroup.id/api/vny/products');

            if ($productsResponse->successful()) {
                $productsData = $productsResponse->json();
                if (isset($productsData['data']) && is_array($productsData['data'])) {
                    $products = collect($productsData['data']);
                } elseif (is_array($productsData)) {
                    $products = collect($productsData);
                }
                \Log::info('Products API successful', ['count' => $products->count()]);
            } else {
                \Log::warning('Products API failed', ['status' => $productsResponse->status()]);
            }
        } catch (\Exception $e) {
            \Log::error('Products API error: ' . $e->getMessage());
        }

        // Fallback to database products if API fails
        if ($products->isEmpty()) {
            $query = Product::where('status', 'active');
            $dbProducts = $query->get();

            if ($dbProducts->count() > 0) {
                $products = $dbProducts->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'description' => $product->description,
                        'price' => $product->price,
                        'image' => $product->image,
                        'category' => $product->category ? $product->category->name : 'Uncategorized'
                    ];
                });
            } else {
                // Final fallback products
                $products = collect([
                    ['id' => 1, 'name' => 'Ulu Paung Mahogani', 'description' => 'Sepatu Dengan Desain Batak Motif Ulu Paung - Mahogani', 'price' => 450000, 'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=300&h=300&fit=crop&crop=center', 'category' => 'Slip On'],
                    ['id' => 2, 'name' => 'Ulu Paung Maroon', 'description' => 'Sepatu Dengan Desain Batak Motif Ulu Paung - Maroon', 'price' => 450000, 'image' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=300&h=300&fit=crop&crop=center', 'category' => 'Sneakers'],
                    ['id' => 3, 'name' => 'Gorga Hitam', 'description' => 'Sepatu Dengan Desain Batak Motif Gorga - Hitam', 'price' => 400000, 'image' => 'https://images.unsplash.com/photo-1551107696-a4b0c5a0d9a2?w=300&h=300&fit=crop&crop=center', 'category' => 'Low Top'],
                    ['id' => 4, 'name' => 'Gorga Navy', 'description' => 'Sepatu Dengan Desain Batak Motif Gorga - Navy', 'price' => 400000, 'image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=300&h=300&fit=crop&crop=center', 'category' => 'Slip On']
                ]);
            }
        }

        // Apply filters
        if (!empty($searchQuery)) {
            $products = $products->filter(function ($product) use ($searchQuery) {
                $name = $product['name'] ?? '';
                $description = $product['description'] ?? '';
                return stripos($name, $searchQuery) !== false ||
                    stripos($description, $searchQuery) !== false;
            });
        }

        if ($selectedCategory !== 'all') {
            $products = $products->filter(function ($product) use ($selectedCategory) {
                $category = $product['category'] ?? '';
                if (is_array($category)) {
                    $category = $category['name'] ?? '';
                }
                return stripos($category, $selectedCategory) !== false;
            });
        }

        if ($priceRange !== 'all') {
            $products = $products->filter(function ($product) use ($priceRange) {
                $price = $product['price'] ?? 0;
                switch ($priceRange) {
                    case 'under_1m':
                        return $price < 1000000;
                    case '1m_3m':
                        return $price >= 1000000 && $price <= 3000000;
                    case '3m_5m':
                        return $price >= 3000000 && $price <= 5000000;
                    case 'over_5m':
                        return $price > 5000000;
                    default:
                        return true;
                }
            });
        }

        // Apply sorting
        switch ($sortBy) {
            case 'price_low_high':
                $products = $products->sortBy('price');
                break;
            case 'price_high_low':
                $products = $products->sortByDesc('price');
                break;
            case 'name_a_z':
                $products = $products->sortBy('name');
                break;
            case 'name_z_a':
                $products = $products->sortByDesc('name');
                break;
            default:
                // Keep original order
                break;
        }

        return view('pages.vny-product-list', compact('products', 'categories', 'selectedCategory', 'searchQuery', 'priceRange', 'sortBy'));
    }    /**
         * Product Detail Page
         */
    public function productDetail($id)
    {
        try {
            // Try to fetch product from API
            $response = Http::timeout(15)->get("https://vanygroup.id/api/vny/products/{$id}");

            if ($response->successful()) {
                $productData = $response->json();
                if (isset($productData['data'])) {
                    $product = $productData['data'];
                    \Log::info('Product detail API successful', ['product_id' => $id]);
                    return view('pages.vny-product-detail', compact('product'));
                }
            }
        } catch (\Exception $e) {
            \Log::error('Product detail API error', [
                'product_id' => $id,
                'error' => $e->getMessage()
            ]);
        }

        // Fallback to local database
        $product = Product::with('category')->find($id);

        if (!$product) {
            abort(404);
        }

        // Convert local product to API format
        $product = [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'description' => $product->description,
            'detailedDescription' => $product->detailed_description ?? $product->description,
            'shortDescription' => $product->short_description ?? $product->description,
            'price' => $product->price,
            'salePrice' => $product->sale_price,
            'sku' => $product->sku,
            'stockQuantity' => $product->stock_quantity ?? 0,
            'manageStock' => true,
            'inStock' => ($product->stock_quantity ?? 0) > 0,
            'status' => $product->status ?? 'active',
            'image' => $product->image ? asset('storage/' . $product->image) : null,
            'mainImage' => $product->main_image ? asset('storage/' . $product->main_image) : ($product->image ? asset('storage/' . $product->image) : null),
            'gallery' => $product->gallery ? (is_array($product->gallery) ? array_map(function ($img) {
                return asset('storage/' . $img); }, $product->gallery) : []) : [],
            'weight' => $product->weight ?? '1000.00',
            'dimensions' => $product->dimensions,
            'categoryId' => $product->category_id,
            'category' => $product->category ? [
                'id' => $product->category->id,
                'name' => $product->category->name,
                'slug' => $product->category->slug
            ] : null,
            'serialNumber' => $product->serial_number ?? 'Limited Edition',
            'colors' => is_array($product->colors) ? $product->colors : ['Default'],
            'sizes' => is_array($product->sizes) ? $product->sizes : ['36', '37', '38', '39', '40', '41', '42', '43', '44'],
            'countryOrigin' => $product->country_origin ?? 'Indonesia',
            'warranty' => $product->warranty ?? '6 Bulan',
            'releaseDate' => $product->release_date ?? $product->created_at->format('Y-m-d'),
            'isFeatured' => $product->is_featured ?? false,
            'createdAt' => $product->created_at,
            'updatedAt' => $product->updated_at
        ];

        \Log::info('Product detail fallback used', ['product_id' => $id]);

        return view('pages.vny-product-detail', compact('product'));
    }

    /**
     * Category Products Page
     */
    public function categoryProducts($slug)
    {
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $products = Product::where('category_id', $category->id)
            ->where('status', 'active')
            ->paginate(12);

        return view('pages.category-products', compact('category', 'products'));
    }

    /**
     * Search Products
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $products = Product::where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('short_description', 'like', "%{$query}%");
            })
            ->paginate(12);

        return view('pages.search', compact('products', 'query'));
    }

    /**
     * Checkout Page
     */
    public function checkout($code = null)
    {
        return view('pages.checkout', compact('code'));
    }

    /**
     * Customer Review Page
     */
    public function customerReview()
    {
        return view('pages.customer-review');
    }

    /**
     * All Customer Reviews
     */
    public function allCustomerReviews()
    {
        return view('pages.all-customer-reviews');
    }

    /**
     * Cart Page
     */
    public function cart()
    {
        return view('pages.vny-cart');
    }

    /**
     * VNY About Page
     */
    public function vnyAbout()
    {
        $aboutData = \App\Models\AboutSetting::getByBrand('vny');

        if (!$aboutData) {
            // Return default data if not found
            $aboutData = $this->getDefaultAboutData('vny');
        }

        return view('pages.brand-about', compact('aboutData'));
    }

    /**
     * VanySongket About Page
     */
    public function vanysongketAbout()
    {
        $aboutData = \App\Models\AboutSetting::getByBrand('vanysongket');

        if (!$aboutData) {
            $aboutData = $this->getDefaultAboutData('vanysongket');
        }

        return view('pages.brand-about', compact('aboutData'));
    }

    /**
     * VanyVilla About Page
     */
    public function vanyvillaAbout()
    {
        $aboutData = \App\Models\AboutSetting::getByBrand('vanyvilla');

        if (!$aboutData) {
            $aboutData = $this->getDefaultAboutData('vanyvilla');
        }

        return view('pages.brand-about', compact('aboutData'));
    }

    /**
     * Get default about data for brands
     */
    private function getDefaultAboutData($brand)
    {
        $defaultData = [
            'vny' => [
                'brand' => 'vny',
                'title' => 'VNY Toba Shoes',
                'subtitle' => 'Memadukan Kearifan Budaya Batak dengan Inovasi Modern dalam Setiap Langkah',
                'description' => 'VNY Toba Shoes lahir dari sebuah visi untuk melestarikan kekayaan budaya Batak.',
                'colors' => ['primary' => '#f59e0b', 'secondary' => '#dc2626', 'accent' => '#ea580c'],
                'hero_data' => ['background' => 'amber-900', 'pattern' => 'traditional'],
                'history_data' => ['established' => '2019', 'customers' => '500+', 'products' => '20+'],
                'philosophy_data' => [
                    ['name' => 'Gorga', 'color' => 'amber', 'meaning' => 'Keindahan, kemakmuran, dan perlindungan'],
                    ['name' => 'Ulos', 'color' => 'red', 'meaning' => 'Kehangatan, kasih sayang, dan persatuan'],
                    ['name' => 'Singa', 'color' => 'orange', 'meaning' => 'Kekuatan, keberanian, dan kepemimpinan']
                ],
                'contact_data' => ['email' => 'info@vnygroup.com', 'phone' => '+62 813-1587-1101', 'location' => 'Medan, North Sumatra']
            ],
            'vanysongket' => [
                'brand' => 'vanysongket',
                'title' => 'VanySongket',
                'subtitle' => 'Melestarikan Keanggunan Songket Tradisional dengan Sentuhan Modern',
                'description' => 'VanySongket menghadirkan koleksi songket premium yang memadukan tradisi dengan desain kontemporer.',
                'colors' => ['primary' => '#dc2626', 'secondary' => '#f59e0b', 'accent' => '#7c3aed'],
                'hero_data' => ['background' => 'red-900', 'pattern' => 'songket'],
                'history_data' => ['established' => '2020', 'customers' => '300+', 'products' => '50+'],
                'philosophy_data' => [
                    ['name' => 'Tradisi', 'color' => 'red', 'meaning' => 'Mempertahankan warisan leluhur'],
                    ['name' => 'Keanggunan', 'color' => 'purple', 'meaning' => 'Elegansi dalam setiap helai benang'],
                    ['name' => 'Modernitas', 'color' => 'amber', 'meaning' => 'Inovasi untuk generasi masa kini']
                ],
                'contact_data' => ['email' => 'info@vanysongket.com', 'phone' => '+62 813-1587-1102', 'location' => 'Palembang, South Sumatra']
            ],
            'vanyvilla' => [
                'brand' => 'vanyvilla',
                'title' => 'VanyVilla',
                'subtitle' => 'Pengalaman Menginap Premium dengan Sentuhan Budaya Nusantara',
                'description' => 'VanyVilla menawarkan akomodasi premium yang memadukan kenyamanan modern dengan kearifan lokal.',
                'colors' => ['primary' => '#065f46', 'secondary' => '#d97706', 'accent' => '#7c2d12'],
                'hero_data' => ['background' => 'green-900', 'pattern' => 'tropical'],
                'history_data' => ['established' => '2021', 'guests' => '1000+', 'properties' => '5'],
                'philosophy_data' => [
                    ['name' => 'Hospitalitas', 'color' => 'green', 'meaning' => 'Keramahan khas Nusantara'],
                    ['name' => 'Sustainability', 'color' => 'amber', 'meaning' => 'Wisata berkelanjutan'],
                    ['name' => 'Authenticity', 'color' => 'orange', 'meaning' => 'Pengalaman budaya autentik']
                ],
                'contact_data' => ['email' => 'info@vanyvilla.com', 'phone' => '+62 813-1587-1103', 'location' => 'Yogyakarta, DIY']
            ]
        ];

        return (object) $defaultData[$brand];
    }
}