<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Camera extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'name',
        'plain_text_token',
        'traffic_direction',
        'latitude',
        'longitude',
    ];

    public function detections()
    {
        return $this->hasMany(Detection::class)->withDefault();
    }
}
