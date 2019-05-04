<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('job_category_id')->unsigned();
            $table->text('description');
            $table->text('location_name');
            $table->string('location_lat', 100);
            $table->string('location_long', 100);
            $table->text('location_description');
            $table->enum('status', [
                'waiting', 
                'cancel',
                'quotation', 
                'quotation_rejected',
                'quotation_agreed',
                'wip', 
                'close'
            ])->default('waiting');

            $table->integer('user_member_id')->unsigned()->nullable();
            //$table->foreign('user_member_id')->references('id')->on('users')->onDelete('set null');

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
        Schema::dropIfExists('jobs');
    }
}
