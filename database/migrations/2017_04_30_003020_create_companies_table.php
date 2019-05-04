<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('sector_business', 30);
            $table->string('user_position_title', 30);
            $table->string('email', 50);
            $table->text('photo');
            $table->string('phone', 20);
            $table->text('address');
            $table->integer('user_member_id')->unsigned()->nullable();
            // $table->foreign('user_member_id')->references('id')->on('users')->onDelete('set null'); 
            
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
        Schema::dropIfExists('companies');
    }
}
