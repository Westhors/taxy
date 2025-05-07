<?php

namespace App\Interfaces;

use App\Repositories\ICrudRepository;

interface AreaRepositoryInterface extends ICrudRepository
{
    public function calculatePrice($pickLat, $pickLng, $dropLat, $dropLng);
}