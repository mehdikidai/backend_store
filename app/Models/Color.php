<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Color extends Model
{
    /** @use HasFactory<\Database\Factories\ColorFactory> */
    use HasFactory;

    protected $fillable = ['name', 'hex_code'];

    public static function boot(): void
    {
        parent::boot();

        static::saved(function ($model): void {
            if (Cache::has('colors')) {
                Cache::forget('colors');
            }
        });

        static::deleted(function ($model): void {
            if (Cache::has('colors')) {
                Cache::forget('colors');
            }
        });
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_colors');
    }
}
