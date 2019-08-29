<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechniciansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technicians', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable($value = true);
            $table->string('email')->nullable($value = true);
            $table->string('password')->nullable($value = true);
            $table->string('code')->nullable($value = true);
            $table->string('mobile')->nullable($value = true);
            $table->text('address')->nullable($value = true);
            $table->string('lat')->nullable($value = true);
            $table->string('lng')->nullable($value = true);
            $table->string('image')->nullable($value = true);
            $table->string('device_token')->nullable($value = true);
            $table->enum('role', ['admin','user','fannie']);    
            $table->string('status');        
            $table->tinyInteger('type')->nullable($value = true);       
            $table->string('lang')->nullable($value = 'ar'); 
                  
            $table->unsignedInteger('user_id')->nullable($value = true);
            $table->unsignedInteger('service_id')->nullable($value = true);
            $table->unsignedInteger('country_id')->nullable($value = true);
            $table->unsignedInteger('city_id')->nullable($value = true);
            $table->unsignedInteger('area_id')->nullable($value = true);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null'); 
            $table->foreign('service_id')->references('id')->on('services')->onDelete('set null'); 
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null'); 
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null'); 
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('set null'); 
            
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
        Schema::dropIfExists('users');
    }
}
