<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'vany_orders';

    protected $fillable = [
        'user_id',
        'order_number',
        'customer_name',
        'customer_email',
        'status',
        'subtotal',
        'discount_amount',
        'total_amount',
        'shipping_address',
        'phone',
        'notes',
        'promo_code_id'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customerReviews()
    {
        return $this->hasMany(CustomerReview::class);
    }

    // Generate QR Code URL for review
    public function getReviewUrlAttribute()
    {
        if ($this->customerReviews()->exists()) {
            $review = $this->customerReviews()->first();
            return url("/review/{$review->review_token}");
        }
        return null;
    }

    // Check if order has review
    public function hasReview()
    {
        return $this->customerReviews()->exists();
    }
}
