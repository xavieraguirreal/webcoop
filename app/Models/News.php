<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'joomla_id', 'title', 'slug', 'excerpt', 'body', 'featured_image',
        'category_id', 'user_id', 'status', 'published_at',
        'meta_title', 'meta_description', 'post_to_instagram',
        'instagram_post_id', 'is_featured', 'language',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'post_to_instagram' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
