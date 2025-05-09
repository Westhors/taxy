<?php

namespace App\Repositories;

use App\Interfaces\AddressRepositoryInterface;
use App\Models\Address;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AddressRepository extends CrudRepository implements AddressRepositoryInterface
{
    protected Model $model;

    public function __construct(Address $model)
    {
        $this->model = $model;
    }
}