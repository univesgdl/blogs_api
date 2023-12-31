<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'description',
        'slug',
        'image'
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function domains()
    {
        return $this->belongsToMany(Domain::class);
    }
}
