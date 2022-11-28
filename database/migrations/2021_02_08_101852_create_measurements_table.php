<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measurements', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->float('height')->nullable();
            $table->float('weight')->nullable();
            $table->float('muscle')->nullable();
            $table->float('fluid')->nullable();
            $table->float('fats')->nullable();
            $table->tinyInteger('target')->nullable();//0 تضخيم , 1 تنقيص وزن , 2 تنشيف
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
        Schema::dropIfExists('measurements');
    }
}
