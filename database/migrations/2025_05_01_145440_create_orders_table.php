<?php

use App\Enums\OrderStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->cascadeOnDelete();
            $table->enum('status', OrderStatus::values())->default(OrderStatus::Pending->value);
            $table->enum('order_type', ['people', 'shipment']);
            $table->enum('transport_type', ['car', 'bike']);
            // Pick-up location
            $table->decimal('pick_lat', 12, 9);
            $table->decimal('pick_lng', 12, 9);
            $table->string('pick_address')->nullable();
            // Drop-off location
            $table->decimal('drop_lat', 12, 9);
            $table->decimal('drop_lng', 12, 9);
            $table->string('drop_address')->nullable();
            // Shipment details
            $table->string('sender_name')->nullable();
            $table->string('sender_phone')->nullable();
            $table->text('sender_remark')->nullable();
            $table->string('receiver_name')->nullable();
            $table->string('receiver_phone')->nullable();
            $table->text('receiver_remark')->nullable();
            $table->string('shipment_type')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('dimensions')->nullable();
            $table->boolean('is_breakable')->nullable();
            //
            $table->timestamp('schedule_time')->nullable();
            $table->decimal('expected_price', 10, 2)->nullable(); 
            $table->decimal('final_price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};