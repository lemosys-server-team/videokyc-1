<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('schedules', function (Blueprint $table) {            
           $table->text('twilio_room_id')->nullable()->after('duration');
           $table->string('admin_by_status')->nullable()->after('twilio_room_id');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('schedules', function (Blueprint $table) {            
            $table->dropColumn('twilio_room_id');
            $table->dropColumn('admin_by_status');
        });
    }
}
