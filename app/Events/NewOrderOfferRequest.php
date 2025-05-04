<?php

namespace App\Events;

use App\Models\OrderRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewOrderOfferRequest implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public OrderRequest $orderRequest;

    public function __construct(OrderRequest $orderRequest)
    {
        $this->orderRequest = $orderRequest;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('user.' . $this->orderRequest->order->user_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'order-offer';
    }

    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->orderRequest->order_id,
            'driver_id' => $this->orderRequest->driver_id,
            'proposed_price' => $this->orderRequest->proposed_price,
            'latitude' => $this->orderRequest->latitude,
            'longitude' => $this->orderRequest->longitude,
            'note' => $this->orderRequest->note,
            'status' => $this->orderRequest->status,
        ];
    }
}


