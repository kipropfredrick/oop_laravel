<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->bigInteger('customer_id');
            $table->string('agent_code')->nullable();
            $table->string('payment_mode');
            $table->string('booking_reference');
            $table->timestamp('date_started');
            $table->timestamp('due_date')->default(now());
            $table->float('total_cost');
            $table->float('amount_paid');
            $table->integer('quantity');
            $table->float('balance');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
