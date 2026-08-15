<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'user_id', 'training_location_id', 'photo_path',
        'latitude', 'longitude', 'distance_meter', 'status', 'attendance_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
