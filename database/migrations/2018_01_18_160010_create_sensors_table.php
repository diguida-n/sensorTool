<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('min_attended',8,2)->nullable();
            $table->decimal('max_attended',8,2)->nullable();
            $table->decimal('longitude',11,7)->nullable();
            $table->decimal('latitude',11,7)->nullable();
            $table->integer('site_id')->unsigned()->nullable();
            $table->integer('enterprise_id')->unsigned()->nullable();
            $table->integer('sensor_catalog_id')->unsigned()->nullable();

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
        Schema::dropIfExists('sensors');
    }
}
