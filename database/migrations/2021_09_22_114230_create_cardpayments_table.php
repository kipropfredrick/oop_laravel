<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardpaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cardpayments', function (Blueprint $table) {
            $table->id();
               $table->string('txncd');
            $table->string('uyt')->nullable();
            $table->string('agt')->nullable();
            $table->string('qwh')->nullable();
            $table->string('ifd')->nullable();
            $table->string('poi')->nullable();
            $table->string('oid')->nullable();
            $table->string('amount')->nullable();
            $table->string('total_amount')->nullable();
            $table->string('channel')->nullable();
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
        Schema::dropIfExists('cardpayments');
    }
}
