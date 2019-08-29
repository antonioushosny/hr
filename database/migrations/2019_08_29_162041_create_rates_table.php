<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('evaluator_from')->nullable($value = true);
            $table->unsignedBigInteger('evaluator_to')->nullable($value = true);
            $table->string('rate')->nullable($value = true);
            $table->string('contact_rate')->nullable($value = true);
            $table->string('time_rate')->nullable($value = true);
            $table->string('work_rate')->nullable($value = true);
            $table->string('cost_rate')->nullable($value = true);
            $table->string('general_character')->nullable($value = true);
            $table->string('date')->nullable($value = true);
            $table->text('notes')->nullable($value = true);
            $table->foreign('evaluator_from')->references('id')->on('users')->onDelete('set null'); 
            $table->foreign('evaluator_to')->references('id')->on('users')->onDelete('set null'); 
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
        Schema::dropIfExists('rates');
    }
}
