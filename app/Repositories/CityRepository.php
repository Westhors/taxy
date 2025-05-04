<?php

namespace App\Repositories;

use App\Interfaces\CityRepositoryInterface;
use App\Models\City;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CityRepository extends CrudRepository implements CityRepositoryInterface
{
    protected Model $model;

    public function __construct(City $model)
    {
        $this->model = $model;
    }
}
