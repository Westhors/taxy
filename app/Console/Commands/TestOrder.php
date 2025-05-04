<?php

namespace App\Console\Commands;

use App\Events\NewOrderRequest;
use App\Models\Order;
use Illuminate\Console\Command;

class TestOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:order {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id =  $this->option('id');
        $order = Order::find($id);
        broadcast(new NewOrderRequest($order, 1));
    }
}