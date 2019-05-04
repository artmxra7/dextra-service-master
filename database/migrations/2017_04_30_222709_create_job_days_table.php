<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_days', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('job_id')->unsigned();
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');

            $table->integer('user_mechanic_id')->unsigned();
            //$table->foreign('user_mechanic_id')->references('id')->on('users')->onDelete('cascade');

            $table->date('date');
            $table->integer('days');
            $table->time('working_hours');
            $table->time('start_working');
            $table->time('finish_working');
            $table->text('photo_attendance');

            $table->string('location_name');
            $table->string('location_lat', 100);
            $table->string('location_long', 100);
            $table->text('location_description');

            $table->text('notes')->nullable();
            $table->text('recommendation')->nullable();
            $table->enum('status', array('waiting','wip','done'))->default('waiting');

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
        Schema::dropIfExists('job_days');
    }
}
