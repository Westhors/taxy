<?php

namespace App\Interfaces;

use App\Models\Order;
use App\Repositories\ICrudRepository;

interface OrderRepositoryInterface extends ICrudRepository
{
    public function createOrder(array $data): Order;
}