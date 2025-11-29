<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HeroSection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HeroSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $heroSections = HeroSection::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($hero) {
                return $this->formatHeroSection($hero);
            });

        return response()->json([
            'data' => $heroSections,
            'message' => 'Hero sections retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'bg_color' => 'nullable|string',
            'text_color' => 'nullable|string',
            'button_text' => 'nullable|string',
            'price' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);

        $heroSection = HeroSection::create($request->all());

        return response()->json([
            'data' => $this->formatHeroSection($heroSection),
            'message' => 'Hero section created successfully'
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $heroSection = HeroSection::findOrFail($id);

        return response()->json([
            'data' => $this->formatHeroSection($heroSection),
            'message' => 'Hero section retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $heroSection = HeroSection::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'bg_color' => 'nullable|string',
            'text_color' => 'nullable|string',
            'button_text' => 'nullable|string',
            'price' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);

        $heroSection->update($request->all());

        return response()->json([
            'data' => $this->formatHeroSection($heroSection),
            'message' => 'Hero section updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $heroSection = HeroSection::findOrFail($id);
        $heroSection->delete();

        return response()->json([
            'message' => 'Hero section deleted successfully'
        ]);
    }

    /**
     * Format hero section data for API response
     */
    private function formatHeroSection($hero)
    {
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
            'sortOrder' => $hero->sort_order,
            'createdAt' => $hero->created_at->toISOString(),
            'updatedAt' => $hero->updated_at->toISOString()
        ];
    }
}
