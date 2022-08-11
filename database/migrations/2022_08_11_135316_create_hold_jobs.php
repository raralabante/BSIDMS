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
        Schema::create('HoldJobs', function (Blueprint $table) {
            $table->id();
            $table->integer('drafting_masters_id')->unsigned()->nullable();
            $table->integer('scheduling_masters_id')->unsigned()->nullable();
            $table->timestamp('hold_start');
            $table->timestamp('hold_end');
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
        Schema::dropIfExists('HoldJobs');
    }
};
