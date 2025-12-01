<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'vany_products';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'detailed_description',
        'short_description',
        'price',
        'sale_price',
        'sku',
        'stock_quantity',
        'manage_stock',
        'in_stock',
        'status',
        'image',
        'main_image',
        'gallery',
        'weight',
        'dimensions',
        'category_id',
        'serial_number',
        'colors',
        'sizes',
        'country_origin',
        'warranty',
        'release_date',
        'is_featured'
    ];

    protected $casts = [
        'price' => 'integer',
        'sale_price' => 'integer',
        'stock_quantity' => 'integer',
        'weight' => 'decimal:2',
        'manage_stock' => 'boolean',
        'in_stock' => 'boolean',
        'gallery' => 'array',
        'colors' => 'array',
        'sizes' => 'array',
        'release_date' => 'date',
        'is_featured' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
