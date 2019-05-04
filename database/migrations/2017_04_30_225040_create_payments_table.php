<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('job_id')->unsigned()->nullable();
            //$table->foreign('job_id')->references('id')->on('jobs')->onDelete('set null');
            $table->integer('order_id')->unsigned()->nullable();
            //$table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->integer('user_member_id')->unsigned()->nullable();
            //$table->foreign('user_member_id')->references('id')->on('users')->onDelete('set null');

            $table->integer('amount');
            $table->string('bank_name', 20);
            $table->string('bank_account', 50);
            $table->string('bank_person_name', 50);
            $table->enum('type', ['jobs', 'orders']);
            $table->enum('status', ['waiting', 'confirmed', 'completed']);
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
        Schema::dropIfExists('payments');
    }
}
