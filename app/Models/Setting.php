<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = Cache::remember('setting.' . $key, 3600, fn () => self::where('key', $key)->first());
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, mixed $value): void
    {
        self::updateOrCreate(['key' => $key], ['value' => is_string($value) ? $value : json_encode($value)]);
        Cache::forget('setting.' . $key);
    }
}
