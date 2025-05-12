<?php

namespace App\Repositories;

use App\Interfaces\AdminRepositoryInterface;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class AdminRepository extends CrudRepository implements AdminRepositoryInterface
{
    protected Model $model;

    public function __construct(Admin $model)
    {
        $this->model = $model;
    }

    public function login(string $email, string $password): ?Admin
    {
        $admin = Admin::where('email', $email)->first();

        if ($admin && Hash::check($password, $admin->password)) {
            return $admin;
        }
        return null;
    }
}
