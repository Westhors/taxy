<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Area extends Model
{
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'radius',
        'price_per_km',
    ];


    public static function getClosestArea($lat, $lng)
    {
        return Area::selectRaw(
            "*,
    (6371 * acos(cos(radians(?)) * cos(radians(latitude)) *
    cos(radians(longitude) - radians(?)) +
    sin(radians(?)) * sin(radians(latitude)))) AS distance",
            [$lat, $lng, $lat]
        )
            ->whereRaw(
                "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) *
        cos(radians(longitude) - radians(?)) +
        sin(radians(?)) * sin(radians(latitude)))) < radius",
                [$lat, $lng, $lat]
            )
            ->orderBy("distance")
            ->first();
    }
}