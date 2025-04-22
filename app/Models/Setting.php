<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory, HasUuids;
    
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'id',
        'key',
        'value',
        'group',
        'description',
        'is_public',
    ];
    
    protected $casts = [
        'is_public' => 'boolean',
    ];
    
    /**
     * Get a setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $cacheKey = 'setting_' . $key;
        
        return Cache::remember($cacheKey, 60 * 60, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            return $setting ? $setting->value : $default;
        });
    }
    
    /**
     * Set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @param string $group
     * @param string|null $description
     * @param bool $isPublic
     * @return Setting
     */
    public static function set($key, $value, $group = 'general', $description = null, $isPublic = true)
    {
        $setting = self::where('key', $key)->first();
        
        if ($setting) {
            $setting->update([
                'value' => $value,
                'group' => $group,
                'description' => $description ?? $setting->description,
                'is_public' => $isPublic,
            ]);
        } else {
            $setting = self::create([
                'id' => \Illuminate\Support\Str::uuid(),
                'key' => $key,
                'value' => $value,
                'group' => $group,
                'description' => $description,
                'is_public' => $isPublic,
            ]);
        }
        
        // Clear cache
        Cache::forget('setting_' . $key);
        
        return $setting;
    }
    
    /**
     * Get all settings by group
     *
     * @param string $group
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByGroup($group)
    {
        return self::where('group', $group)->get();
    }
    
    /**
     * Get all public settings
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getPublic()
    {
        return self::where('is_public', true)->get();
    }
}
