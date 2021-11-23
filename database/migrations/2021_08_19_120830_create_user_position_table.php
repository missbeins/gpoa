<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_position', function (Blueprint $table) {
            $table->id('user_position_id');
            $table->unsignedBigInteger('position_title_id');
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('organization_id')->references('organization_id')->on('organizations')->onDelete('cascade');
            $table->foreign('position_title_id')->references('position_title_id')->on('position_titles')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('user_position');
    }
}
