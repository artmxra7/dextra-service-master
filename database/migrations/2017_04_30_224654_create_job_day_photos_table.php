<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobDayPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_day_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->text('photo');
            $table->text('description')->nullable();

            $table->integer('job_day_id')->unsigned();
            //$table->foreign('job_day_id')->references('id')->on('job_days')->onDelete('cascade');

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
        Schema::dropIfExists('job_day_photos');
    }
}
