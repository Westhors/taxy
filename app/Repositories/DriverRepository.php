<?php

namespace App\Repositories;

use App\Interfaces\DriverRepositoryInterface;

use App\Models\Admin;
use App\Models\Driver;
use Illuminate\Database\Eloquent\Model;

class DriverRepository extends CrudRepository implements DriverRepositoryInterface
{
    protected Model $model;

    public function __construct(Driver $model)
    {
        $this->model = $model;
    }
}
