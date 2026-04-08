<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'joomla_id', 'parent_id', 'name', 'slug', 'description', 'language', 'sort_order',
    ];

    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
