<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'featured_image',
        'duration', 'modality', 'has_certificate', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'has_certificate' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
