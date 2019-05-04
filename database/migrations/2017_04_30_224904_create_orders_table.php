<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_member_id')->unsigned();
            //$table->foreign('user_member_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('user_sales_id')->unsigned()->nullable();
            //$table->foreign('user_sales_id')->references('id')->on('users')->onDelete('cascade');

            $table->text('discount_description')->nullable();
            $table->float('discount_percent')->nullable();
            $table->string('discount_coupon', 20)->nullable();

            $table->float('total_price');
            $table->text('address');
            $table->string('city');
            $table->text('notes')->nullable();
            $table->enum('status', [
                'WAITING_OFFER',
                'OFFER_RECEIVED',
                'OFFER_AGREED',
                'OFFER_REJECTED',
                'WAITING_PAYMENT_CONFIRMED',
                'PAYMENT_CONFIRMED',
                'DELIVERY_PROCESS',
                'DELIVERY_RECEIVED',
                'ORDER_FINISHED',
                'ORDER_CANCELED',
            ])->default('WAITING_OFFER');
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
        Schema::dropIfExists('orders');
    }
}
