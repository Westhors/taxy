<?php

namespace App\Interfaces;

use App\Models\Order;
use App\Repositories\ICrudRepository;

interface OrderRepositoryInterface extends ICrudRepository
{
    public function createOrder(array $data): Order;
    public function acceptOrderRequest(array $data): ?Order;
    public function sendOrderToNearBy(Order $order, $pick_lat = null, $pick_lng = null): void;
}
