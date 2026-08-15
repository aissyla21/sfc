<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'winner_name', 'category', 'year', 'location', 'description', 'image_path'];
    protected $casts = [
        'image_path' => 'array',
    ];
}
