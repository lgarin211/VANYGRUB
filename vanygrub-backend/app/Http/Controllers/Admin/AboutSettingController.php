<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aboutSettings = AboutSetting::orderBy('brand')->paginate(10);
        return view('admin.about-settings.index', compact('aboutSettings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = ['vny', 'vanysongket', 'vanyvilla'];
        $existingBrands = AboutSetting::pluck('brand')->toArray();
        $availableBrands = array_diff($brands, $existingBrands);

        return view('admin.about-settings.create', compact('availableBrands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'required|unique:about_settings,brand',
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string',
            'description' => 'required|string',
            'hero_background' => 'nullable|string',
            'primary_color' => 'nullable|string',
            'secondary_color' => 'nullable|string',
            'accent_color' => 'nullable|string',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'history_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'philosophy_images' => 'nullable|array',
            'philosophy_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image uploads
        $heroImage = null;
        if ($request->hasFile('hero_image')) {
            $heroImage = $request->file('hero_image')->store('about-images', 'public');
        }

        $historyImage = null;
        if ($request->hasFile('history_image')) {
            $historyImage = $request->file('history_image')->store('about-images', 'public');
        }

        $philosophyImages = [];
        if ($request->hasFile('philosophy_images')) {
            foreach ($request->file('philosophy_images') as $index => $image) {
                if ($image) {
                    $philosophyImages[$index] = $image->store('about-images', 'public');
                }
            }
        }

        $data = [
            'brand' => $request->brand,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'hero_image' => $heroImage,
            'history_image' => $historyImage,
            'philosophy_images' => $philosophyImages,
            'colors' => [
                'primary' => $request->primary_color ?? '#f59e0b',
                'secondary' => $request->secondary_color ?? '#dc2626',
                'accent' => $request->accent_color ?? '#ea580c'
            ],
            'hero_data' => [
                'background' => $request->hero_background ?? 'amber-900',
                'pattern' => 'traditional'
            ],
            'history_data' => [
                'established' => $request->established ?? date('Y'),
                'customers' => $request->customers ?? '100+',
                'products' => $request->products ?? '10+',
                'years' => $request->years ?? '1+'
            ],
            'philosophy_data' => $this->parsePhilosophyData($request),
            'contact_data' => [
                'email' => $request->contact_email ?? 'info@vanygroup.com',
                'phone' => $request->contact_phone ?? '+62 813-1587-1101',
                'location' => $request->contact_location ?? 'Indonesia'
            ],
            'is_active' => $request->has('is_active')
        ];

        AboutSetting::create($data);

        return redirect()->route('admin.about-settings.index')
            ->with('success', 'About setting created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AboutSetting $aboutSetting)
    {
        return view('admin.about-settings.show', compact('aboutSetting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AboutSetting $aboutSetting)
    {
        return view('admin.about-settings.edit', compact('aboutSetting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AboutSetting $aboutSetting)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string',
            'description' => 'required|string',
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string',
            'primary_hero_color' => 'nullable|string',
            'secondary_hero_color' => 'nullable|string',
            'main_history_title' => 'nullable|string',
            'history_posters.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'history_years.*' => 'nullable|string',
            'history_titles.*' => 'nullable|string',
            'history_descriptions.*' => 'nullable|string',
            'history_colors.*' => 'nullable|string',
            'history_bgcolors.*' => 'nullable|string',
            'philosophy_colors.*' => 'nullable|string',
            'philosophy_names.*' => 'nullable|string',
            'philosophy_meanings.*' => 'nullable|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'contact_location' => 'required|string',
        ]);

        $data = [
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'hero_data' => [
                'title' => $request->hero_title ?? $request->title,
                'subtitle' => $request->hero_subtitle ?? $request->subtitle,
                'primary_color' => $request->primary_hero_color ?? '#1e40af',
                'secondary_color' => $request->secondary_hero_color ?? '#1d4ed8',
                'background' => 'gradient',
                'pattern' => 'modern'
            ],
            'history_data' => $this->parseHistoryData($request),
            'philosophy_data' => $this->parsePhilosophyDataNew($request),
            'contact_data' => [
                'email' => $request->contact_email,
                'phone' => $request->contact_phone,
                'location' => $request->contact_location
            ],
            'is_active' => true
        ];

        // Handle image uploads
        if ($request->hasFile('hero_image')) {
            // Delete old image if exists
            if ($aboutSetting->hero_image) {
                Storage::delete('public/' . $aboutSetting->hero_image);
            }
            $data['hero_image'] = $request->file('hero_image')->store('about-images', 'public');
        }

        if ($request->hasFile('history_image')) {
            // Delete old image if exists
            if ($aboutSetting->history_image) {
                Storage::delete('public/' . $aboutSetting->history_image);
            }
            $data['history_image'] = $request->file('history_image')->store('about-images', 'public');
        }

        if ($request->hasFile('philosophy_images')) {
            // Delete old images if exist
            if ($aboutSetting->philosophy_images) {
                foreach ($aboutSetting->philosophy_images as $image) {
                    Storage::delete('public/' . $image);
                }
            }

            $philosophyImages = [];
            foreach ($request->file('philosophy_images') as $file) {
                $philosophyImages[] = $file->store('about-images', 'public');
            }
            $data['philosophy_images'] = $philosophyImages;
        }

        $aboutSetting->update($data);

        return redirect()->route('admin.about-settings.index')
            ->with('success', 'About setting updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AboutSetting $aboutSetting)
    {
        $aboutSetting->delete();

        return redirect()->route('admin.about-settings.index')
            ->with('success', 'About setting deleted successfully.');
    }

    /**
     * Parse philosophy data from request
     */
    private function parsePhilosophyData($request)
    {
        $philosophyData = [];

        if ($request->has('philosophy_names')) {
            $names = $request->philosophy_names;
            $colors = $request->philosophy_colors ?? [];
            $meanings = $request->philosophy_meanings ?? [];

            for ($i = 0; $i < count($names); $i++) {
                if (!empty($names[$i])) {
                    $philosophyData[] = [
                        'name' => $names[$i],
                        'color' => $colors[$i] ?? 'gray',
                        'meaning' => $meanings[$i] ?? '',
                        'icon' => 'star'
                    ];
                }
            }
        }

        return $philosophyData;
    }

    /**
     * Parse history timeline data from request
     */
    private function parseHistoryData($request)
    {
        $historyData = [
            'main_title' => $request->main_history_title ?? 'Our Story',
            'timeline' => []
        ];

        $years = $request->history_years ?? [];
        $titles = $request->history_titles ?? [];
        $descriptions = $request->history_descriptions ?? [];
        $colors = $request->history_colors ?? [];
        $bgcolors = $request->history_bgcolors ?? [];
        $existingPosters = $request->history_poster_existing ?? [];

        if (!empty($years)) {
            for ($i = 0; $i < count($years); $i++) {
                if (!empty($years[$i])) {
                    $posterPath = $existingPosters[$i] ?? '';
                    
                    // Handle poster upload
                    if ($request->hasFile('history_posters') && isset($request->file('history_posters')[$i])) {
                        // Delete old poster if exists
                        if ($posterPath) {
                            Storage::delete('public/' . $posterPath);
                        }
                        $posterPath = $request->file('history_posters')[$i]->store('history-posters', 'public');
                    }

                    $historyData['timeline'][] = [
                        'poster' => $posterPath,
                        'tahun' => $years[$i],
                        'title' => $titles[$i] ?? '',
                        'deskripsi' => $descriptions[$i] ?? '',
                        'color' => $colors[$i] ?? '#1e40af',
                        'bgcolor' => $bgcolors[$i] ?? '#f8fafc'
                    ];
                }
            }
        }

        return $historyData;
    }

    /**
     * Parse philosophy data with new structure (color, name, meaning)
     */
    private function parsePhilosophyDataNew($request)
    {
        $philosophyData = [];
        $colors = $request->philosophy_colors ?? [];
        $names = $request->philosophy_names ?? [];
        $meanings = $request->philosophy_meanings ?? [];

        if (!empty($names)) {
            for ($i = 0; $i < count($names); $i++) {
                if (!empty($names[$i])) {
                    $philosophyData[] = [
                        'color' => $colors[$i] ?? '#1e40af',
                        'name' => $names[$i],
                        'meaning' => $meanings[$i] ?? ''
                    ];
                }
            }
        }

        return $philosophyData;
    }
}
