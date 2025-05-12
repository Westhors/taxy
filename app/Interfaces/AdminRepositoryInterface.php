<?php

namespace App\Interfaces;

use App\Models\Admin;
use App\Repositories\ICrudRepository;

interface AdminRepositoryInterface extends ICrudRepository
{
    public function login(string $email, string $password): ?Admin;
}
