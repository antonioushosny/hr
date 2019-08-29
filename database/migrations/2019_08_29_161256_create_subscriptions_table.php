<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('date')->nullable($value = true);
            $table->string('image')->nullable($value = true);
            $table->string('subscription_id')->nullable($value = true);
            $table->string('fannie_id')->nullable($value = true);
            $table->foreign('fannie_id')->references('id')->on('users')->onDelete('set null'); 
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('set null'); 
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
        Schema::dropIfExists('subscriptions');
    }
}
