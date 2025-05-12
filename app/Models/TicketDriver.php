<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketDriver extends Model
{
     use HasFactory , SoftDeletes;

    protected $fillable = [
        'driver_id', 'name', 'phone', 'email', 'message',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
