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
        Schema::create('scheduling_masters', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('job_number');
            $table->string('client_name');
            $table->string('address');
            $table->string('type');
            $table->string('prestart');
            $table->string('stage');
            $table->string('brand')->nullable();
            $table->string('job_type')->nullable();
            $table->string('category')->nullable();
            $table->float('floor_area')->nullable();
            $table->string('prospect')->nullable();
            $table->boolean('hitlist')->nullable();
            $table->string('status');
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('submitted_by')->nullable();
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
        Schema::dropIfExists('scheduling_masters');
    }
};
