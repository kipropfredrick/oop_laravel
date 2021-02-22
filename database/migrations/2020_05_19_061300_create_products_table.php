<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id');
            $table->bigInteger('subcategory_id');
            $table->bigInteger('brand_id');
            $table->bigInteger('vendor_id');
            $table->bigInteger('agent_id')->nullable();
            $table->bigInteger('clicks')->default('0');
            $table->string('product_name');
            $table->string('product_code');
            $table->string('slug');
            $table->string('status')->default('pending');
            $table->float('product_price',10,2);
            $table->longText('highlights',255);
            $table->longText('description',255);
            $table->integer('quantity');
            $table->string('product_image');
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
        Schema::dropIfExists('products');
    }
}
