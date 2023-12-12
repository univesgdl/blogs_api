<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
