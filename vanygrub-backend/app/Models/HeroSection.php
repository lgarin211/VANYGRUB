<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSection extends Model
{
    use HasFactory;

    protected $table = 'vany_hero_sections';

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image',
        'bg_color',
        'text_color',
        'button_text',
        'price',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];
}
