<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'name'];

    public static function boot(): void
    {
        parent::boot();

        static::saved(function ($model): void {
            if (Cache::has('categories')) {
                Cache::forget('categories');
            }
        });

        static::deleted(function ($model): void {
            if (Cache::has('categories')) {
                Cache::forget('categories');
            }
        });
    }

}
