<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Models\TicketDriver;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketDriverController extends Controller
{
    use HttpResponses;

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'email' => 'nullable|email',
                'message' => 'required|string',
            ]);

            $ticket = TicketDriver::create([
                'driver_id' => Auth::id(),
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'message' => $request->message,
            ]);
            return $this->success($ticket, 'Ticket submitted successfully');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
