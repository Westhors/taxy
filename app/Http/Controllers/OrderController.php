<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Events\NewOrderOfferRequest;
use App\Events\NewOrderRequest;
use App\Http\Requests\Users\Orders\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Interfaces\OrderRepositoryInterface;
use App\Traits\HttpResponses;
use App\Helpers\JsonResponse;
use App\Http\Requests\Driver\Orders\CreateOrderRequestRequest;
use App\Http\Requests\Users\Orders\AcceptOrderRequestRequest;
use App\Http\Requests\Users\Orders\CancelOrderRequest;
use App\Models\Driver;
use App\Models\Order;
use App\Models\OrderRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    use HttpResponses;

    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function show(Request $request,  $order_id)
    {
        try {
            $order = $this->orderRepository->find($order_id);
            if ($order && $order->user_id === Auth::guard('user')->id()) {
                return $this->success(new OrderResource($order));
            } else {
                return $this->error(null, 'You can\'t access to this order');
            }
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function createOrder(CreateOrderRequest $request)
    {
        try {
            $order = $this->orderRepository->createOrder($request->validated());

            return $order
                ? $this->success(new OrderResource($order), 'Order created successfully')
                : $this->error('Order creation faild.', 400);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function acceptOrderRequest(AcceptOrderRequestRequest $request)
    {
        try {
            $order = $this->orderRepository->acceptOrderRequest($request->validated());

            return $order
                ? $this->success(new OrderResource($order), 'Order accepted successfully')
                : $this->error('Invalid or non-pending order request.', 400);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function history(Request $request)
    {
        try {
            $driver = $request->user();

            $orders = $driver->orders()->get();

            return $this->success(OrderResource::collection($orders));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function cancelOrder(CancelOrderRequest $request)
    {
        try {
            $order = $this->orderRepository->cancelOrderRequest($request->validated());

            return $order
                ? $this->success(new OrderResource($order), 'Order canceled successfully')
                : $this->error('Order canceled faild.', 400);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function createOrderDriver(CreateOrderRequestRequest $request, Order $order)
    {
        try {
            $driver = auth()->guard('driver')->user();

            $orderRequest = $this->orderRepository->createOrderRequest([
                'order_id' => $order->id,
                'driver_id' => $driver->id,
                'proposed_price' => $request->proposed_price,
                'note' => $request->note,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            $orderRequest->load(['driver']);

            broadcast(new NewOrderOfferRequest($orderRequest))->toOthers();

            return $this->success($orderRequest, 'Request sent');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function historyOrders(Request $request)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:all,upcoming,completed,failed',
        ]);
        try {
            $category = $validated['status'] ?? 'all';

            $statuses = OrderStatus::categories()[$category] ?? [];

            $userId = auth()->guard('user')->id();

            $orders = $this->orderRepository
                ->getUserOrdersByStatuses($userId, $statuses)
                ->paginate(10);

            return $this->success(OrderResource::collection($orders));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function startDelivery($id)
    {
        $driver = auth('driver')->user();

        $order = Order::where('id', $id)
            ->where('driver_id', $driver->id)
            ->first();

            if (!$order) {
                return $this->error(null, 'Unauthorized.', 401);
            }

        if ($order->status->value !== 'accepted') {
            return $this->error(null, 'Order status must be accepted before starting delivery.', 400);
        }

        $order->status = 'in_transit';
        $order->save();

        return $this->success($order, 'Delivery has been started successfully.');
    }
}
