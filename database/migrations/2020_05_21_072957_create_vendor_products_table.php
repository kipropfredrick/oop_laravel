<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id');
            $table->bigInteger('subcategory_id');
            $table->bigInteger('vendor_id')->nullable();
            $table->string('product_name');
            $table->string('product_code');
            $table->string('slug');
            $table->float('product_price',10,2);
            $table->longText('highlights');
            $table->longText('description');
            $table->integer('quantity');
            $table->string('product_image');
            $table->string('status')->default('pending');
            $table->bigInteger('reviews')->default(0);
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
        Schema::dropIfExists('vendor_products');
    }
}
