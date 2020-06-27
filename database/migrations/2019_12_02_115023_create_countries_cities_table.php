<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
        });
        
        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('country_id')->default(0);
            $table->string('title')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
        Schema::dropIfExists('countries');
    }
}
