<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'content',
        'extra',
        // 'width',
        // 'height',
        // 'align',
        //'position',
        'post_id',
    ];
}
