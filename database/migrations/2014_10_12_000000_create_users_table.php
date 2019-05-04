<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->enum('role', ['member', 'sales', 'mechanic', 'admin'])->default('member');
            $table->string('city')->nullable();
            $table->string('api_token', 60)->unique()->nullable();
            $table->text('fcm_token');
            $table->string('verification_code', 5)->nullable();

            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            $table->integer('user_sales_id')->unsigned()->nullable();
            $table->foreign('user_sales_id')->references('id')->on('users')->onDelete('set null');


            $table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
