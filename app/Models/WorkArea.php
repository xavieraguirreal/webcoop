<?php

namespace App\Models;

use App\Services\WatermarkService;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class WorkArea extends Model
{
    use HasTranslations;

    public array $translatable = ['name', 'short_description', 'description'];
    protected static function booted(): void
    {
        static::saved(function (WorkArea $area) {
            if ($area->wasChanged('featured_image') && $area->featured_image) {
                WatermarkService::apply($area->featured_image);
            }
        });
    }

    public const GROUPS = [
        'talleres-productivos' => 'Talleres Productivos',
        'produccion-agropecuaria' => 'Producción Agropecuaria',
        'educacion-formacion' => 'Educación y Formación',
        'bienestar-comunidad' => 'Bienestar y Comunidad',
    ];

    protected $fillable = [
        'name', 'slug', 'group', 'short_description', 'description',
        'featured_image', 'icon', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
