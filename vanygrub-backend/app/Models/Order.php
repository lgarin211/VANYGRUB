<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'vany_orders';

    protected $fillable = [
        'order_number',
        'user_id',
        'session_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'shipping_postal_code',
        'payment_method',
        'payment_status',
        'order_status',
        'subtotal',
        'shipping_cost',
        'tax_amount',
        'total_amount',
        'notes',
        'tracking_number'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
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

    /**
     * Generate a unique order number
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'VG';
        $timestamp = now()->format('ymd');
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        return $prefix . $timestamp . $random;
    }

    /**
     * Get formatted order status
     */
    public function getFormattedStatusAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', $this->order_status ?? 'pending'));
    }
}
