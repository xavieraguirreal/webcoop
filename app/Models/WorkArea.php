<?php

namespace App\Models;

use App\Services\WatermarkService;
use Illuminate\Database\Eloquent\Model;

class WorkArea extends Model
{
    protected static function booted(): void
    {
        static::saved(function (WorkArea $area) {
            if ($area->wasChanged('featured_image') && $area->featured_image) {
                WatermarkService::apply($area->featured_image);
            }
        });
    }

    protected $fillable = [
        'name', 'slug', 'short_description', 'description',
        'featured_image', 'icon', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
