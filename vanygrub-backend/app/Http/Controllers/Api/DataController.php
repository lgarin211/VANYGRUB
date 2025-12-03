<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\HeroSection;
use App\Models\ProductGrid;
use Illuminate\Http\Request;

class DataController extends Controller
{
    /**
     * Get all data in format suitable for Next.js constants
     */
    public function getAllData()
    {
        return response()->json([
            'categories' => $this->getFormattedCategories(),
            'products' => $this->getFormattedProducts(),
            'heroSections' => $this->getFormattedHeroSections(),
            'productGrid' => $this->getFormattedProductGrid(),
            'homeData' => $this->getHomeData()
        ]);
    }

    /**
     * Get home page data
     */
    public function getHomeData()
    {
        $categories = $this->getFormattedCategories();
        $featuredProducts = $this->getFormattedProducts(true);
        $heroSections = $this->getFormattedHeroSections();
        $productGrid = $this->getFormattedProductGrid();

        return response()->json([
            'heroSections' => $heroSections,
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
            'productGrid' => $productGrid
        ]);
    }

    /**
     * Get formatted categories
     */
    private function getFormattedCategories()
    {
        return Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'image' => $category->image ? url('storage/' . $category->image) : null,
                    'isActive' => $category->is_active,
                    'sortOrder' => $category->sort_order
                ];
            })
            ->toArray();
    }

    /**
     * Get formatted products
     */
    private function getFormattedProducts($featured = false)
    {
        $query = Product::with('category')
            ->where('status', 'active')
            ->where('in_stock', true);

        if ($featured) {
            $query->where('is_featured', true);
        }

        return $query->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'description' => $product->description,
                    'detailedDescription' => $product->detailed_description,
                    'shortDescription' => $product->short_description,
                    'price' => (float) $product->price,
                    'salePrice' => $product->sale_price ? (float) $product->sale_price : null,
                    'sku' => $product->sku,
                    'stockQuantity' => $product->stock_quantity,
                    'manageStock' => $product->manage_stock,
                    'inStock' => $product->in_stock,
                    'status' => $product->status,
                    'image' => $product->image ? url('storage/' . $product->image) : null,
                    'mainImage' => $product->main_image ? url('storage/' . $product->main_image) : null,
                    'gallery' => $product->gallery ? array_map(function ($image) {
                        return url('storage/' . $image);
                    }, $product->gallery) : [],
                    'weight' => $product->weight,
                    'dimensions' => $product->dimensions,
                    'categoryId' => $product->category_id,
                    'category' => $product->category ? [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                        'slug' => $product->category->slug
                    ] : null,
                    'serialNumber' => $product->serial_number,
                    'colors' => $product->colors ?: [],
                    'sizes' => $product->sizes ?: [],
                    'countryOrigin' => $product->country_origin,
                    'warranty' => $product->warranty,
                    'releaseDate' => $product->release_date ? $product->release_date->format('Y-m-d') : null,
                    'isFeatured' => $product->is_featured
                ];
            })
            ->toArray();
    }

    /**
     * Get formatted hero sections
     */
    private function getFormattedHeroSections()
    {
        return HeroSection::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($hero) {
                return [
                    'id' => $hero->id,
                    'title' => $hero->title,
                    'subtitle' => $hero->subtitle,
                    'description' => $hero->description,
                    'image' => $hero->image ? url('storage/' . $hero->image) : null,
                    'bgColor' => $hero->bg_color,
                    'textColor' => $hero->text_color,
                    'buttonText' => $hero->button_text,
                    'price' => $hero->price,
                    'isActive' => $hero->is_active,
                    'sortOrder' => $hero->sort_order
                ];
            })
            ->toArray();
    }

    /**
     * Get formatted product grid
     */
    private function getFormattedProductGrid()
    {
        $products = ProductGrid::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'subtitle' => $item->subtitle,
                    'image' => $item->image ? url('storage/' . $item->image) : null,
                    'buttonText' => $item->button_text,
                    'bgColor' => $item->bg_color,
                    'bgImage' => $item->bg_image,
                    'isActive' => $item->is_active,
                    'sortOrder' => $item->sort_order
                ];
            })
            ->toArray();

        return [
            'items' => $products,
            'sizeConfig' => [
                'itemsPerRow' => [
                    'desktop' => 3,
                    'tablet' => 2,
                    'mobile' => 1
                ],
                'spacing' => 'medium'
            ]
        ];
    }

    /**
     * Get VNY specific data
     */
    public function getVnyData()
    {
        return response()->json([
            'success' => true,
            'data' => $this->getFormattedProducts()
        ]);
    }

    /**
     * Get VNY site configuration
     */
    public function getVnySiteConfig()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'siteName' => 'VNY Store',
                'heroTitle' => 'Welcome to VNY Store',
                'heroDescription' => 'Discover premium sneakers and streetwear collections. Quality meets style in every piece we curate for you.',
                'sectionTitle' => 'Featured Products',
                'brandColors' => [
                    'primary' => '#8B0000',
                    'secondary' => '#DC143C',
                    'accent' => '#ff6b35'
                ],
                'navigation' => [
                    ['name' => 'HOME', 'url' => '/'],
                    ['name' => 'PRODUCT', 'url' => '/products'],
                    ['name' => 'ABOUT VNY', 'url' => '/about'],
                    ['name' => 'GALLERY', 'url' => '/gallery']
                ]
            ]
        ]);
    }
}
