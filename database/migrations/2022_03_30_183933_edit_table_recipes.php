<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditTableRecipes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recipies', function (Blueprint $table) {
            $table->unsignedBigInteger('ingredient_primary_id')->nullable()->after('group_id'); // مكون اساسي
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
