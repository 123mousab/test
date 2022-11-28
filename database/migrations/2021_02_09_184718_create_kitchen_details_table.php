<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKitchenDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kitchen_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('kitchen_id');
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('recipie_id');
            $table->unsignedInteger('cuisine_id');
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
        Schema::dropIfExists('kitchen_details');
    }
}
