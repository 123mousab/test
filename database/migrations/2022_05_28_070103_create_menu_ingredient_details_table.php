<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuIngredientDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_ingredient_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('menu_id');
            $table->unsignedInteger('ingredient_id')->nullable();
            $table->unsignedInteger('recipie_protein_id')->nullable();
            $table->unsignedInteger('cuisine_protein_id')->nullable();
            $table->unsignedInteger('recipie_carb_id')->nullable();
            $table->unsignedInteger('cuisine_carb_id')->nullable();
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
        Schema::dropIfExists('menu_ingredient_details');
    }
}
