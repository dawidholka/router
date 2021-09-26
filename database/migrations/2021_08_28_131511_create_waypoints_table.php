<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaypointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waypoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->nullable()->constrained('routes')->cascadeOnDelete();
            $table->foreignId('point_id')->nullable()->constrained('points')->cascadeOnDelete();
            $table->unsignedInteger('stop_number');
            $table->string('status')->default('undelivered');
            $table->string('content')->nullable();
            $table->integer('quantity')->nullable();
            $table->dateTime('delivered_time')->nullable();
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
        Schema::dropIfExists('waypoints');
    }
}
