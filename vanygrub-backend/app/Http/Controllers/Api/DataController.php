<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\HeroSection;
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

        return response()->json([
            'heroSections' => $heroSections,
            'categories' => $categories,
            'featuredProducts' => $featuredProducts
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
                    'image' => $category->image,
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
                    'image' => $product->image,
                    'mainImage' => $product->main_image,
                    'gallery' => $product->gallery ?: [],
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
                    'image' => $hero->image,
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
}
