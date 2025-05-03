<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewOrderRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Order $order;
    public $driverId;
    /**
     * Create a new event instance.
     */
    public function __construct($order, $driverId)
    {
        $this->order = $order;
        $this->driverId = $driverId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('driver.' . $this->driverId),  // Use as 'private-driver.1'
        ];
    }

    public function broadcastAs(): string
    {
        return 'new-order'; // Use as '.new-order'
    }

    public function broadcastWith(): array
    {
        return [
            'order' => $this->order,
        ];
    }
}