<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category')
            ->where('status', 'active')
            ->where('in_stock', true);

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by featured
        if ($request->has('featured') && $request->featured) {
            $query->where('is_featured', true);
        }

        // Search
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhere('short_description', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($product) {
                return $this->formatProduct($product);
            });

        return response()->json([
            'data' => $products,
            'message' => 'Products retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:vany_products,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:vany_categories,id',
            'image' => 'nullable|string',
            'stock_quantity' => 'integer|min:0',
            'sku' => 'nullable|string|unique:vany_products,sku'
        ]);

        $product = Product::create($request->all());

        return response()->json([
            'data' => $this->formatProduct($product->load('category')),
            'message' => 'Product created successfully'
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with('category')->findOrFail($id);

        return response()->json([
            'data' => $this->formatProduct($product),
            'message' => 'Product retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:vany_products,slug,' . $id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:vany_categories,id',
            'image' => 'nullable|string',
            'stock_quantity' => 'integer|min:0',
            'sku' => 'nullable|string|unique:vany_products,sku,' . $id
        ]);

        $product->update($request->all());

        return response()->json([
            'data' => $this->formatProduct($product->load('category')),
            'message' => 'Product updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }

    /**
     * Get product by slug
     */
    public function getBySlug(string $slug)
    {
        $product = Product::with('category')
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        return response()->json([
            'data' => $this->formatProduct($product),
            'message' => 'Product retrieved successfully'
        ]);
    }

    /**
     * Get featured products
     */
    public function getFeatured()
    {
        $products = Product::with('category')
            ->where('is_featured', true)
            ->where('status', 'active')
            ->where('in_stock', true)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($product) {
                return $this->formatProduct($product);
            });

        return response()->json([
            'data' => $products,
            'message' => 'Featured products retrieved successfully'
        ]);
    }

    /**
     * Format product data for API response
     */
    private function formatProduct($product)
    {
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
            'isFeatured' => $product->is_featured,
            'createdAt' => $product->created_at->toISOString(),
            'updatedAt' => $product->updated_at->toISOString()
        ];
    }
}
