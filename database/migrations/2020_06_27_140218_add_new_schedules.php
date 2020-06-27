<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
                $table->string('duration')->nullable()->after('status');
                $table->text('image_pen')->nullable()->after('duration');
                $table->text('image_adhar')->nullable()->after('image_pen');
                $table->text('image_photo')->nullable()->after('image_adhar');
                $table->text('ss01')->nullable()->after('image_photo');
                $table->text('ss02')->nullable()->after('ss01');
                $table->text('ss03')->nullable()->after('ss02');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropColumn('duration');
        $table->dropColumn('image_pen');
        $table->dropColumn('image_adhar');
        $table->dropColumn('image_photo');
        $table->dropColumn('ss01');
        $table->dropColumn('ss02');
        $table->dropColumn('ss03');
    }
}
