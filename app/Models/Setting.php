<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group'];

    /**
     * Retrieve a setting value by key, with an optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever('setting_' . $key, function () use ($key, $default) {
            $row = static::where('key', $key)->first();
            return $row ? $row->value : $default;
        });
    }

    /**
     * Store or update a setting value.
     */
    public static function set(string $key, mixed $value, string $group = 'general'): void
    {
        Cache::forget('setting_' . $key);
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }

    /**
     * Return all settings as a key→value array.
     */
    public static function allKeyed(): array
    {
        return static::all()->pluck('value', 'key')->toArray();
    }
}
