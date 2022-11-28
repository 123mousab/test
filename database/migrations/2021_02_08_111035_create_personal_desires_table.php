<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalDesiresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_desires', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->string('notes')->nullable();
            $table->unsignedInteger('cuisine_id')->nullable();
            $table->unsignedInteger('ingredient_id')->nullable();
            $table->unsignedInteger('recipie_id')->nullable();
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
        Schema::dropIfExists('personal_desires');
    }
}
