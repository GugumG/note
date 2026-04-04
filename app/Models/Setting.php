<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['user_id', 'key', 'value'];

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get a setting value by key for the current authenticated user.
     */
    public static function get($key, $default = null)
    {
        $userId = auth()->id();
        if (!$userId) return $default;

        $setting = self::where('user_id', $userId)->where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value by key for the current authenticated user.
     */
    public static function set($key, $value)
    {
        $userId = auth()->id();
        if (!$userId) return null;

        return self::updateOrCreate(
            ['user_id' => $userId, 'key' => $key], 
            ['value' => $value]
        );
    }
}
