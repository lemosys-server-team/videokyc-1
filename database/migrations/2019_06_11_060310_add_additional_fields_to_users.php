<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalFieldsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('verification_token')->nullable()->after('password');
            $table->text('profile_picture')->nullable()->after('verification_token');
            $table->tinyInteger('is_active')->default(1)->after('profile_picture');
            $table->softDeletes();            
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
            $table->dropColumn('verification_token');
            $table->dropColumn('profile_picture');
            $table->dropColumn('is_active');
            $table->dropSoftDeletes();
        });
    }
}
