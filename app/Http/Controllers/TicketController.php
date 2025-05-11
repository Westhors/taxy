<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\Profile\CreateTicketRequest;
use App\Interfaces\TicketRepositoryInterface;
use App\Repositories\TicketRepository;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    use HttpResponses;

    protected $repository;

    public function __construct(TicketRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $tickets = $this->repository->all(Auth::guard('user')->id());
        return $this->success($tickets, 'Tickets fetched successfully');
    }

    public function store(CreateTicketRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::guard('user')->id();

        $ticket = $this->repository->create($data);

        return $this->success($ticket, 'Ticket submitted successfully');
    }

    public function destroy($id)
    {
        $ticket = $this->repository->find($id);

        if (!$ticket) {
            return $this->error(null, 'Ticket not found or unauthorized', 404);
        }

        $ticket->delete();

        return $this->success(null, 'Ticket deleted successfully');
    }
}
