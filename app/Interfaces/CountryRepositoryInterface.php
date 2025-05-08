<?php

namespace App\Interfaces;

use App\Repositories\ICrudRepository;

interface CountryRepositoryInterface extends ICrudRepository
{
    public function getByCountryCode(string $code);
}