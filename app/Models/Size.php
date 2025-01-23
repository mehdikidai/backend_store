<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

class Size extends Model
{
    /** @use HasFactory<\Database\Factories\SizeFactory> */
    use HasFactory;

    protected $fillable = ['name'];


    public static function boot(): void
    {

        parent::boot();

        static::saving(function ($model): void {
            if (Cache::has('sizes')) {
                Cache::forget('sizes');
            }
        });

        static::deleted(function ($model): void {
            if (Cache::has('sizes')) {
                Cache::forget('sizes');
            }
        });

    }



    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_sizes');
    }
}
