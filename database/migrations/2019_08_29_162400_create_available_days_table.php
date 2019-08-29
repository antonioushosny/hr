<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvailableDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('available_days', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('day')->nullable($value = true);
            $table->string('from')->nullable($value = true);
            $table->string('to')->nullable($value = true);
            $table->string('fannie_id')->nullable($value = true);
            $table->foreign('fannie_id')->references('id')->on('users')->onDelete('set null'); 
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
        Schema::dropIfExists('available_days');
    }
}
