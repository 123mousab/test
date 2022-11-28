<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwoColumnRecipies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recipies', function (Blueprint $table) {
            $table->boolean('protein')->default(0)->after('image');
            $table->boolean('carb')->default(0)->after('protein');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recipies', function (Blueprint $table) {
            //
        });
    }
}
