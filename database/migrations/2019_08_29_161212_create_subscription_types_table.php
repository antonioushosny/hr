<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_ar')->nullable($value = true);
            $table->string('name_en')->nullable($value = true);
            $table->string('no_month')->nullable($value = true);
            $table->string('cost')->nullable($value = true);
            $table->string('status', ['active', 'not_active'])->nullable($value = true)->default('active');
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
        Schema::dropIfExists('subscription_types');
    }
}
