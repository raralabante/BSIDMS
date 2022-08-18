<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

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
        Schema::create('rejected_jobs', function (Blueprint $table) {
            $table->id();
            $table->integer('drafting_masters_id')->unsigned()->nullable();
            $table->integer('scheduling_masters_id')->unsigned()->nullable();
            $table->string('rejected_by')->nullable();
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
        Schema::dropIfExists('rejected_jobs');
    }
};
