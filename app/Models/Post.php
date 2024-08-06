<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {
    protected $fillable = [
        'thumbnail',
        'title',
        'content',
        'color',
        'slug',
        'category_id',
        'published',
        'tags'
    ];

    protected $casts = ['tags' => 'array'];

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
