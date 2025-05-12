<?php

namespace App\Repositories;

use App\Interfaces\TicketRepositoryInterface;
use App\Models\Ticket;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketRepository extends CrudRepository implements TicketRepositoryInterface
{
    protected Model $model;

    public function __construct(Ticket $model)
    {
        $this->model = $model;
    }
}
