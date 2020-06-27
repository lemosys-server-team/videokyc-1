<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLatitudeToCities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->string('latitude', 45)->nullable()->after('title');
            $table->string('longitude', 45)->nullable()->after('latitude');
            $table->string('timezone', 100)->nullable()->after('longitude');
            $table->tinyInteger('is_daylight_saving')->nullable()->default(0)->after('timezone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->dropColumn('timezone');
            $table->dropColumn('is_daylight_saving');
        });
    }
}
