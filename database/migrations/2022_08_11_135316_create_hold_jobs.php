<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('hold_jobs')){
        Schema::create('Hold_Jobs', function (Blueprint $table) {
            $table->id();
            $table->integer('drafting_masters_id')->unsigned()->nullable();
            $table->integer('scheduling_masters_id')->unsigned()->nullable();
            $table->dateTime('hold_start')->nullable();
            $table->dateTime('hold_end')->nullable();
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Hold_Jobs');
    }
};
