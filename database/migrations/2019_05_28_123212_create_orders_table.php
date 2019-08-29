<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable($value = true);
            $table->unsignedInteger('fannie_id')->nullable($value = true);
            $table->unsignedInteger('service_id')->nullable($value = true);
            $table->string('lat')->nullable($value = true);
            $table->string('lng')->nullable($value = true);
            $table->text('address')->nullable($value = true);
            $table->text('rejected_reason')->nullable($value = true);
            $table->string('date')->nullable($value = true);
            $table->string('time')->nullable($value = true);
            $table->string('notes')->nullable($value = true);
            $table->string('status')->nullable($value = true);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null'); 
            $table->foreign('fannie_id')->references('id')->on('users')->onDelete('set null'); 
            $table->foreign('service_id')->references('id')->on('services')->onDelete('set null'); 
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
        Schema::dropIfExists('orders');
    }
}
