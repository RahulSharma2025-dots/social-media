<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description'
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_topics')
            ->withTimestamps();
    }

    public function getPostsCountAttribute()
    {
        return $this->posts()->count();
    }
} 