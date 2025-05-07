<?php

namespace App\Repositories;

use App\Enums\OrderStatus;
use App\Events\NewOrderRequest;
use App\Interfaces\OrderRepositoryInterface;
use App\Models\Driver;
use App\Models\Order;
use App\Models\OrderRequest;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderRepository extends CrudRepository implements OrderRepositoryInterface
{
    protected Model $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $order = Order::create([
                'user_id' => auth('user')->id(),
                // 'driver_id' => $data['driver_id'] ?? null,
                'status' => OrderStatus::Pending->value,
                'order_type' => $data['order_type'],
                'transport_type' => $data['transport_type'],
                'pick_lat' => $data['pick_lat'],
                'pick_lng' => $data['pick_lng'],
                'pick_address' => $data['pick_address'],
                'drop_lat' => $data['drop_lat'],
                'drop_lng' => $data['drop_lng'],
                'drop_address' => $data['drop_address'],
                'sender_name' => $data['sender_name'],
                'sender_phone' => $data['sender_phone'],
                'sender_remark' => $data['sender_remark'],
                'receiver_name' => $data['receiver_name'],
                'receiver_phone' => $data['receiver_phone'],
                'receiver_remark' => $data['receiver_remark'],
                'shipment_type' => $data['shipment_type'],
                'shipment_details' => $data['shipment_details'],
                'weight' => $data['weight'],
                'dimensions' => $data['dimensions'],
                'is_breakable' => $data['is_breakable'],
                'schedule_time' => $data['schedule_time'],
                'kms_num' => $data['kms_num'],
                'expected_price' => $data['expected_price'],
            ]);

            $this->sendOrderToNearBy($order, $data['pick_lat'], $data['pick_lng']);

            return $order;
        });
    }

    public function createOrderRequest(array $data): ?OrderRequest
    {
        return OrderRequest::create($data);
    }

    public function acceptOrderRequest(array $data): ?Order
    {
        return DB::transaction(function () use ($data) {
            $orderRequest = OrderRequest::with('order')->find($data['order_request_id']);
            if (!$orderRequest) {
                return null;
            }

            $order = $orderRequest->order;

            if ($order->status !== OrderStatus::Pending) {
                return null;
            }

            $order->acceptOrder($orderRequest);

            return $order;
        });
    }

    public function cancelOrderRequest(array $data): ?Order
    {
        return DB::transaction(function () use ($data) {
            $order = Order::find($data['order_id']);

            if (!$order) {
                return null;
            }

            $order->cancelOrder();

            return $order;
        });
    }

    public function sendOrderToNearBy(Order $order, $pick_lat = null, $pick_lng = null): void
    {
        $nearbyDrivers = Driver::nearby($pick_lat, $pick_lng)->get();

        foreach ($nearbyDrivers as $driver) {
            try {
                broadcast(new NewOrderRequest($order, $driver->id));
            } catch (Exception $e) {
                Log::error('Error broadcasting to driver ' . $driver->id . ': ' . $e->getMessage());
            }
        }
    }
}