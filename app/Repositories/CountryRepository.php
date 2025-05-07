<?php

namespace App\Repositories;

use App\Interfaces\CountryRepositoryInterface;
use App\Models\Country;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CountryRepository extends CrudRepository implements CountryRepositoryInterface
{
    protected Model $model;

    public function __construct(Country $model)
    {
        $this->model = $model;
    }

    public function getByCountryCode(string $code)
    {
        return Country::where('country_code', $code)->first();
    }
}