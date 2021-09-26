<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('street');
            $table->string('building_number');
            $table->string('city');
            $table->string('apartament')->nullable();
            $table->string('intercom')->nullable();
            $table->string('delivery_time')->nullable();
            $table->string('phone')->nullable();
            $table->double('lat', 10, 7)->nullable();
            $table->double('long', 10, 7)->nullable();
            $table->text('note')->nullable();
            $table->text('driver_note')->nullable();
            $table->boolean('lock_geo')->default(false);
            $table->string('quantity')->nullable();
            $table->timestamp('last_used_at')->nullable();
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
        Schema::dropIfExists('points');
    }
}
