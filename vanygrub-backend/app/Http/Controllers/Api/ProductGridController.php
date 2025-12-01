<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductGrid;
use Illuminate\Http\Request;

class ProductGridController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productGrids = ProductGrid::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($item) {
                return $this->formatProductGridItem($item);
            });

        return response()->json([
            'data' => [
                'products' => $productGrids->toArray(),
                'sizeConfig' => [
                    'minHeight' => 300,
                    'maxHeight' => 380,
                    'minWidth' => 350,
                    'maxWidth' => 500,
                    'animationInterval' => 8000,
                    'transitionDuration' => '3000ms'
                ]
            ],
            'message' => 'Product grid data retrieved successfully'
        ]);
    }

    /**
     * Get product grid data formatted for frontend
     */
    public function getGridData()
    {
        return $this->index();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'image' => 'required|string',
            'button_text' => 'required|string|max:50',
            'bg_color' => 'required|string|max:255',
            'bg_image' => 'required|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        $productGrid = ProductGrid::create($validated);

        return response()->json([
            'data' => $this->formatProductGridItem($productGrid),
            'message' => 'Product grid item created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $productGrid = ProductGrid::findOrFail($id);

        return response()->json([
            'data' => $this->formatProductGridItem($productGrid),
            'message' => 'Product grid item retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $productGrid = ProductGrid::findOrFail($id);

        $validated = $request->validate([
            'title' => 'string|max:255',
            'subtitle' => 'string|max:255',
            'image' => 'string',
            'button_text' => 'string|max:50',
            'bg_color' => 'string|max:255',
            'bg_image' => 'string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        $productGrid->update($validated);

        return response()->json([
            'data' => $this->formatProductGridItem($productGrid),
            'message' => 'Product grid item updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $productGrid = ProductGrid::findOrFail($id);
        $productGrid->delete();

        return response()->json([
            'message' => 'Product grid item deleted successfully'
        ]);
    }

    /**
     * Format product grid item for API response
     */
    private function formatProductGridItem($item)
    {
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
    }
}
