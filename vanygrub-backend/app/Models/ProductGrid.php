<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGrid extends Model
{
    use HasFactory;

    protected $table = 'vany_product_grids';

    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'button_text',
        'bg_color',
        'bg_image',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];
}
