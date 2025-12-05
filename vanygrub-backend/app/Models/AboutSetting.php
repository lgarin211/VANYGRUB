<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand',
        'title',
        'subtitle',
        'description',
        'hero_data',
        'hero_image',
        'history_data',
        'history_image',
        'philosophy_data',
        'philosophy_images',
        'quality_data',
        'vision_mission_data',
        'cta_data',
        'contact_data',
        'images',
        'colors',
        'is_active',
    ];

    protected $casts = [
        'hero_data' => 'array',
        'history_data' => 'array',
        'philosophy_data' => 'array',
        'philosophy_images' => 'array',
        'quality_data' => 'array',
        'vision_mission_data' => 'array',
        'cta_data' => 'array',
        'contact_data' => 'array',
        'images' => 'array',
        'colors' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get about setting by brand
     */
    public static function getByBrand($brand)
    {
        return self::where('brand', $brand)->where('is_active', true)->first();
    }

    /**
     * Get all active brands
     */
    public static function getActiveBrands()
    {
        return self::where('is_active', true)->pluck('brand', 'id');
    }
}
