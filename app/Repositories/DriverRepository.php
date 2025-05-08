<?php

namespace App\Repositories;

use App\Interfaces\DriverRepositoryInterface;

use App\Models\Admin;
use App\Models\Driver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DriverRepository extends CrudRepository implements DriverRepositoryInterface
{
    protected Model $model;

    public function __construct(Driver $model)
    {
        $this->model = $model;
    }


    public function setPassword(array $data): ?Driver
    {
        $email = $data['email'];
        $newPassword = $data['password'];

        $driver = Driver::where('email', $email)->first();

        if (!$driver || $driver->password) {
            return null;
        }

        $driver->password = Hash::make($newPassword);
        $driver->save();

        return $driver;
    }


    public function completeProfile(array $data): Driver
    {
        $driver = Auth::guard('driver')->user();
        $driver->update($data);
        return $driver;
    }

    public function catDetails(array $data): Driver
    {
        $driver = Auth::guard('driver')->user();
        $driver->update($data);
        return $driver;
    }
}
