<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSMSLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_m_s_logs', function (Blueprint $table) {
            $table->id();
            $table->string('receiver');
            $table->longText('message');
            $table->string('status')->default('sent')->enum('sent','delivered','failed');
            $table->string('comment')->default('sent');
            $table->string('type')->default('notification');
            $table->string('cost')->default('not_confirmed');
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
        Schema::dropIfExists('s_m_s_logs');
    }
}
