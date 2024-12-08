<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TgGeoPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'latitude',
        'longitude',
        'route_description',
        'geoposition_id',
    ];
}