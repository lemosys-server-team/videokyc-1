<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address1')->nullable()->after('is_active');
            $table->string('address2')->nullable()->after('address1');
            $table->unsignedBigInteger('state_id')->nullable()->after('address2');
            $table->unsignedBigInteger('city_id')->nullable()->after('state_id');
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            Schema::dropIfExists('primary_address');
            Schema::dropIfExists('secondary_address');
            Schema::dropIfExists('state_id');
            Schema::dropIfExists('city_id');
          });
    }
}
