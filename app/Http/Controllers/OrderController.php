<?php

namespace App\Http\Controllers;

use App\Events\NewOrderRequest;
use App\Http\Requests\Users\Orders\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Interfaces\OrderRepositoryInterface;
use App\Traits\HttpResponses;
use App\Helpers\JsonResponse;
use App\Models\Driver;
use Exception;

class OrderController extends Controller
{
    use HttpResponses;

    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function createOrder(CreateOrderRequest $request)
    {
        try {
            $order = $this->orderRepository->createOrder($request->validated());

            if ($order) {
                return $this->success(new OrderResource($order), 'Order created successfully');
            }

            return $this->error('Order creation faild', 400);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}