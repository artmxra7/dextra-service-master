<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobMechanicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_mechanics', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('job_id')->unsigned();
            //$table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');

            $table->integer('user_mechanic_id')->unsigned();
            //$table->foreign('user_mechanic_id')->references('id')->on('users')->onDelete('cascade');

            $table->enum('status', ['waiting', 'approved', 'rejected']);

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
        Schema::dropIfExists('job_mechanics');
    }
}
