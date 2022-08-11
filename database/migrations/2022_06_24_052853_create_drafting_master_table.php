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
        Schema::create('drafting_masters', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('job_number');
            $table->string('client_name');
            $table->string('address');
            $table->string('type');
            $table->date('ETA');
            $table->string('brand')->nullable();
            $table->string('job_type')->nullable();
            $table->string('category')->nullable();
            $table->float('floor_area')->nullable();
            $table->string('prospect')->nullable();
            $table->boolean('six_stars')->nullable();
            $table->string('status');
            $table->string('hold_status');
            $table->dateTime('six_stars_submitted_at')->nullable();
            $table->dateTime('six_stars_received_at')->nullable();
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
        Schema::dropIfExists('drafting_masters');
    }
};
