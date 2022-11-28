<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('city_id')->nullable();
            $table->unsignedInteger('branch_id')->nullable();
            $table->unsignedInteger('driver_id')->nullable();
            $table->string('home_address')->nullable();
            $table->boolean('period')->nullable();
            $table->string('home_number')->nullable();
            $table->string('company')->nullable();
            $table->string('group')->nullable();
            $table->string('address')->nullable();
            $table->string('image')->nullable();
            $table->string('notes')->nullable();
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('deliveries');
    }
}
