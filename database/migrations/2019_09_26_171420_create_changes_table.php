<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('changes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable($value = true);
            $table->text('reason')->nullable($value = true);
            $table->unsignedBigInteger('employee_id')->nullable($value = true);
            $table->unsignedInteger('department_id')->nullable($value = true);
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null'); 
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
        Schema::dropIfExists('changes');
    }
}
