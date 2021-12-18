<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camera extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mqtt_topic',
        'traffic_direction',
        'latitude',
        'longitude',
    ];

    public function detections()
    {
        return $this->hasMany(Detection::class)->withDefault();
    }
}
