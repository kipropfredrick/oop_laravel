<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfluencerCommissionTotalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('influencer_commission_totals', function (Blueprint $table) {
            $table->id();
            $table->integer('influencer_id');
            $table->float('total_commission',10,2);
            $table->float('commission_paid',10,2);
            $table->float('pending_payment',10,2);
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
        Schema::dropIfExists('influencer_commission_totals');
    }
}
