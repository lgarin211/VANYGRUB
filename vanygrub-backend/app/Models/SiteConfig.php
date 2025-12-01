<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiteConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'description',
        'is_active'
    ];

    protected $casts = [
        'value' => 'json',
        'is_active' => 'boolean'
    ];

    /**
     * Get config value by group and key
     */
    public static function getValue(string $group, string $key, $default = null)
    {
        $config = self::where('group', $group)
            ->where('key', $key)
            ->where('is_active', true)
            ->first();

        return $config ? $config->value : $default;
    }

    /**
     * Set config value
     */
    public static function setValue(string $group, string $key, $value, string $type = 'text', string $description = null)
    {
        return self::updateOrCreate(
            ['group' => $group, 'key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description,
                'is_active' => true
            ]
        );
    }

    /**
     * Get all configs by group
     */
    public static function getByGroup(string $group)
    {
        return self::where('group', $group)
            ->where('is_active', true)
            ->get()
            ->pluck('value', 'key');
    }

    /**
     * Scope for active configs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific group
     */
    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group);
    }
}
