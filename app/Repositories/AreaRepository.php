<?php

namespace App\Repositories;

use App\Interfaces\AreaRepositoryInterface;
use App\Models\Area;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AreaRepository extends CrudRepository implements AreaRepositoryInterface
{
    protected Model $model;

    public function __construct(Area $model)
    {
        $this->model = $model;
    }

    public function calculatePrice($pickLat, $pickLng, $dropLat, $dropLng)
    {
        $pickArea = Area::getClosestArea($pickLat, $pickLng);
        $dropArea = Area::getClosestArea($dropLat, $dropLng);

        if (!$pickArea || !$dropArea) {
            return null;
        }

        $distance = $this->haversineDistance($pickLat, $pickLng, $dropLat, $dropLng);

        $pricePerKm = ($pickArea->price_per_km + $dropArea->price_per_km) / 2;

        $estimatedPrice = round($distance * $pricePerKm, 2);
        return [
            'pick_area' => $pickArea->name,
            'drop_area' => $dropArea->name,
            'price_per_km' => round($pricePerKm, 2),
            'distance_km' => round($distance, 2),
            'estimated_price' => $estimatedPrice,
        ];
    }

    protected function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}