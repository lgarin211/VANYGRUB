<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'vany_cart_items';

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
        'color',
        'size',
        'unit_price',
        'total_price'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2'
    ];

    protected $appends = ['formatted_unit_price', 'formatted_total_price'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    // Accessor untuk format harga
    public function getFormattedUnitPriceAttribute()
    {
        return 'Rp ' . number_format($this->unit_price, 0, ',', '.');
    }

    public function getFormattedTotalPriceAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    // Mutator untuk menghitung total price otomatis
    public function setQuantityAttribute($value)
    {
        $this->attributes['quantity'] = $value;
        if (isset($this->attributes['unit_price'])) {
            $this->attributes['total_price'] = $value * $this->attributes['unit_price'];
        }
    }

    public function setUnitPriceAttribute($value)
    {
        $this->attributes['unit_price'] = $value;
        if (isset($this->attributes['quantity'])) {
            $this->attributes['total_price'] = $this->attributes['quantity'] * $value;
        }
    }

    // Scope for session-based cart
    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    // Scope for user-based cart
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Helper method untuk mendapatkan cart berdasarkan session atau user
    public static function getCartItems($sessionId = null, $userId = null)
    {
        $query = static::with(['product']);

        if ($userId) {
            $query->byUser($userId);
        } elseif ($sessionId) {
            $query->bySession($sessionId);
        } else {
            return collect([]);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    // Helper method untuk menambah item ke cart
    public static function addItem($data)
    {
        $existingItem = static::where(function ($query) use ($data) {
            if (isset($data['user_id'])) {
                $query->where('user_id', $data['user_id']);
            } else {
                $query->where('session_id', $data['session_id']);
            }
        })
            ->where('product_id', $data['product_id'])
            ->where('color', $data['color'] ?? null)
            ->where('size', $data['size'] ?? null)
            ->first();

        if ($existingItem) {
            // Update quantity jika item sudah ada
            $existingItem->quantity += $data['quantity'];
            $existingItem->total_price = $existingItem->quantity * $existingItem->unit_price;
            $existingItem->save();
            return $existingItem;
        } else {
            // Buat item baru
            return static::create($data);
        }
    }

    // Helper method untuk update quantity
    public function updateQuantity($quantity)
    {
        $this->quantity = $quantity;
        $this->total_price = $quantity * $this->unit_price;
        $this->save();
        return $this;
    }

    // Helper method untuk clear cart
    public static function clearCart($sessionId = null, $userId = null)
    {
        $query = static::query();

        if ($userId) {
            $query->byUser($userId);
        } elseif ($sessionId) {
            $query->bySession($sessionId);
        } else {
            return false;
        }

        return $query->delete();
    }
}
