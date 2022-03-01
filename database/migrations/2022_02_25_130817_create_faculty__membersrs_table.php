<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacultyMembersrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculty_members', function (Blueprint $table) {
            $table->id('officer_id');
            $table->foreignId('position_title_id');
            $table->date('term_start');
            $table->date('term_end');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable()->default(NULL);
            $table->string('signature')->nullable()->default(NULL);
            $table->unsignedTinyInteger('status');
            $table->timestamps();
            
            $table->foreign('position_title_id')->references('position_title_id')->on('position_titles');

            $table->unsignedBigInteger('organization_id')->nullable()->default(NULL);
            $table->foreign('organization_id')->references('organization_id')->on('organizations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faculty__membersrs');
    }
}
