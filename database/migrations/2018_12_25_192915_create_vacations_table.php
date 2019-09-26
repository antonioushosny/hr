<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable($value = true);
            $table->string('from')->nullable($value = true);
            $table->string('to')->nullable($value = true);
            $table->string('days')->nullable($value = true);
            $table->string('type')->nullable($value = true);
            $table->string('notes')->nullable($value = true);
            $table->enum('status', ['active', 'not_active'])->default('active');
            $table->unsignedBigInteger('employee_id')->nullable($value = true);
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade'); 
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
        Schema::dropIfExists('vacations');
    }
}
