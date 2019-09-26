<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable($value = true);
            $table->string('email')->nullable($value = true);
            $table->string('password')->nullable($value = true);
            $table->string('mobile')->nullable($value = true);
            $table->string('national_id')->nullable($value = true);
            $table->string('mac_address')->nullable($value = true);
            $table->string('net_salary')->nullable($value = true);
            $table->string('cross_salary')->nullable($value = true);
            $table->string('insurance')->nullable($value = true);
            $table->string('annual_vacations')->nullable($value = true);
            $table->string('accidental_vacations')->nullable($value = true);
             $table->string('image')->nullable($value = true);
            $table->string('device_token')->nullable($value = true);
            $table->enum('role', ['admin','hr']);    
            $table->string('status');        
            $table->tinyInteger('type')->nullable($value = true); 
            $table->softDeletes();      
            $table->string('lang')->nullable($value = 'ar'); 
            $table->unsignedInteger('department_id')->nullable($value = true);
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null'); 
            $table->rememberToken();
            
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
        Schema::dropIfExists('employees');
    }
}
