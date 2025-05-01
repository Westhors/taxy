<?php

namespace App\Http\Resources;

use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'driver_id' => $this->driver_id,
            'status' => $this->status,
            'status_label' =>  $this->status?->getLabel(),
            'order_type' => $this->order_type,
            'transport_type' => $this->transport_type,
            // Pick-up location
            'pick_lat' => $this->pick_lat,
            'pick_lng' => $this->pick_lng,
            'pick_address' => $this->pick_address,
            // Drop-off location
            'drop_lat' => $this->drop_lat,
            'drop_lng' => $this->drop_lng,
            'drop_address' => $this->drop_address,
            // Shipment details
            'sender_name' => $this->sender_name,
            'sender_phone' => $this->sender_phone,
            'sender_remark' => $this->sender_remark,
            'receiver_name' => $this->receiver_name,
            'receiver_phone' => $this->receiver_phone,
            'receiver_remark' => $this->receiver_remark,
            'shipment_type' => $this->shipment_type,
            'weight' => $this->weight,
            'dimensions' => $this->dimensions,
            'is_breakable' => $this->is_breakable,
            // Schedule and pricing
            'schedule_time' => optional($this->schedule_time)->format('Y-m-d H:i:s'),
            'expected_price' => $this->expected_price,
            'final_price' => $this->final_price,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
        ];
    }
}