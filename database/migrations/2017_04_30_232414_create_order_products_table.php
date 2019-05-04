<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('order_id')->unsigned()->nullable();
           // $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');

            $table->integer('product_id')->unsigned()->nullable();
            //$table->foreign('product_id')->references('id')->on('products')->onDelete('set null');

            $table->string('product_title', 50);
            $table->string('product_brand', 50);
            $table->enum('product_type', ['pcs', 'box'])->default('pcs');
            $table->string('no_product', 50);
            $table->string('sn_product', 50);
            $table->float('price');
            $table->integer('qty');
            
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
        Schema::dropIfExists('order_products');
    }
}
