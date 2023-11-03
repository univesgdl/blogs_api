<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;


    protected $fillable = [
        'title',
        'extract',
        'featured_image',
        'slug',
        'published_at',
        'published'
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function domains()
    {
        return $this->belongsToMany(Domain::class);
    }

    public function blocks()
    {
        return $this->hasMany(Block::class, 'post_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

    public function meta()
    {
        return $this->hasOne(Meta::class, 'entity_id', 'id');
    }
}
