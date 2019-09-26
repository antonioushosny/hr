<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('date')->nullable($value = true);
            $table->string('time')->nullable($value = true);
            $table->string('project_name')->nullable($value = true);
            $table->string('status')->nullable($value = true);
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
        Schema::dropIfExists('tasks');
    }
}
