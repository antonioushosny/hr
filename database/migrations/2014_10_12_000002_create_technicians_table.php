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
            
            $table->string('renewal_date')->nullable($value = true);
            $table->string('available')->nullable($value = true);
            $table->text('brief')->nullable($value = true);
            $table->unsignedInteger('user_id')->nullable($value = true);
            $table->unsignedInteger('service_id')->nullable($value = true);
            $table->unsignedInteger('country_id')->nullable($value = true);
            $table->unsignedInteger('city_id')->nullable($value = true);
            $table->unsignedInteger('area_id')->nullable($value = true);
            $table->unsignedInteger('nationality_id ')->nullable($value = true);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null'); 
            $table->foreign('service_id')->references('id')->on('services')->onDelete('set null'); 
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null'); 
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null'); 
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('set null'); 
            $table->foreign('nationality_id')->references('id')->on('nationalities')->onDelete('set null'); 
            
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
        Schema::dropIfExists('technicians');
    }
}
