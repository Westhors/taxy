<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'driver_id',
        'status',
        'order_type',
        'transport_type',
        'pick_lat',
        'pick_lng',
        'pick_address',
        'drop_lat',
        'drop_lng',
        'drop_address',
        'sender_name',
        'sender_phone',
        'sender_remark',
        'receiver_name',
        'receiver_phone',
        'receiver_remark',
        'shipment_type',
        'weight',
        'dimensions',
        'is_breakable',
        'schedule_time',
        'expected_price',
        'final_price',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'pick_lat' => 'decimal:9',
        'pick_lng' => 'decimal:9',
        'drop_lat' => 'decimal:9',
        'drop_lng' => 'decimal:9',
        'weight'   => 'decimal:2',
        'expected_price' => 'decimal:2',
        'final_price' => 'decimal:2',
        'schedule_time' => 'datetime',
        'is_breakable' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}