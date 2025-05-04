<?php

namespace App\Http\Controllers;

use App\Events\NewOrderOfferRequest;
use App\Events\NewOrderRequest;
use App\Http\Requests\Users\Orders\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Interfaces\OrderRepositoryInterface;
use App\Traits\HttpResponses;
use App\Helpers\JsonResponse;
use App\Models\Driver;
use App\Models\Order;
use App\Models\OrderRequest;
use Exception;
use Illuminate\Http\Request;

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

    public function createOrderDriver(Request $request, Order $order)
    {
        try {
            $request->validate([
                'proposed_price' => 'required|numeric|min:0',
                'note' => 'nullable|string',
                'latitude' => 'nullable|string',
                'longitude' => 'nullable|string',
            ]);
            $driver = auth()->guard('driver')->user();
            $orderRequest = OrderRequest::create([
                'order_id' => $order->id,
                'driver_id' => $driver->id,
                'proposed_price' => $request->proposed_price,
                'note' => $request->note,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
            broadcast(new NewOrderOfferRequest($orderRequest))->toOthers();
            return $this->success($orderRequest, 'Request sent');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
