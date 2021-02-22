<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickupLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pickup_locations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('county_id');
            $table->string('town');
            $table->string('center_name');
            $table->string('street');
            $table->string('building');
            $table->string('direction_tip');
            $table->string('contact_no');
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
        Schema::dropIfExists('pickup_locations');
    }
}
