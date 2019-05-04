<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('title', 50);
            $table->string('slug', 100);
            $table->string('no_product', 50);
            $table->string('sn_product', 50);
            $table->string('photo', 100);
            $table->text('description');
            $table->integer('price_piece')->default(0);
            $table->integer('price_box')->default(0);
            $table->boolean('is_active');
            $table->boolean('is_stock_available');
            $table->enum('type', ['pcs', 'box']);

            $table->integer('product_unit_model_id')->unsigned()->nullable();
            // $table->foreign('product_unit_model_id')->references('id')
            //         ->on('product_unit_models')->onDelete('set null');

            $table->integer('product_brand_id')->unsigned()->nullable();
            // $table->foreign('product_brand_id')->references('id')
            //         ->on('product_brands')->onDelete('set null');

            $table->softDeletes();
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
