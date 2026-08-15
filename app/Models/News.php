<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    
    protected $fillable = ['title', 'content', 'date', 'icon', 'image_path'];
    protected $casts = [
        'image_path' => 'array',
    ];
}
