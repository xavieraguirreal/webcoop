<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasTranslations;

    public array $translatable = ['title', 'body', 'meta_title', 'meta_description'];
    protected $fillable = [
        'title', 'slug', 'body', 'featured_image', 'template',
        'is_active', 'meta_title', 'meta_description', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
