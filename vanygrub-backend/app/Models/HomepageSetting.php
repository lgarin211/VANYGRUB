<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_name',
        'is_active',
        'display_order',
        'welcome_badge',
        'welcome_title',
        'welcome_tagline',
        'welcome_description',
        'welcome_image',
        'highlight_1_number',
        'highlight_1_text',
        'highlight_2_number',
        'highlight_2_text',
        'welcome_button_text',
        'welcome_button_link',
        'brand_section_title',
        'brand_section_description',
        'brand_featured_title',
        'brand_featured_description',
        'brand_featured_image',
        'brand_button_text',
        'brand_button_link',
        'value_1_number',
        'value_1_title',
        'value_1_description',
        'value_1_image',
        'value_1_button_text',
        'value_1_button_link',
        'value_2_number',
        'value_2_title',
        'value_2_description',
        'value_2_image',
        'value_2_button_text',
        'value_2_button_link',
        'portfolio_title',
        'portfolio_subtitle',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function getBySection($sectionName)
    {
        return self::where('section_name', $sectionName)
            ->where('is_active', true)
            ->first();
    }
}
