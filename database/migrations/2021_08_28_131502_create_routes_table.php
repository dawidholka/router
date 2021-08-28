<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->date('delivery_time');
            $table->unsignedInteger('driver_id');
            $table->unsignedInteger('status');
            $table->unsignedInteger('optimize_status');
            $table->text('note')->nullable();
            $table->string('distance')->nullable();
            $table->string('time')->nullable();
            $table->timestamp('driver_downloaded_at')->nullable();
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
        Schema::dropIfExists('routes');
    }
}
