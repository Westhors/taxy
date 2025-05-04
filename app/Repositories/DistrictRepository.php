<?php

namespace App\Repositories;

use App\Interfaces\DistrictRepositoryInterface;
use App\Models\District;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DistrictRepository extends CrudRepository implements DistrictRepositoryInterface
{
    protected Model $model;

    public function __construct(District $model)
    {
        $this->model = $model;
    }
}
