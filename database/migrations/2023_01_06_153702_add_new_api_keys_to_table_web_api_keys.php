<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('web_api_keys', function (Blueprint $table) {
            $table->string('aktiwator_api_key')->after('secondLine_api_key');
            $table->string('fiveSim_api_key')->after('aktiwator_api_key');
            $table->string('onlineSms_api_key')->after('fiveSim_api_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('web_api_keys', function (Blueprint $table) {
            $table->dropColumn('aktiwator_api_key');
            $table->dropColumn('fiveSim_api_key');
            $table->dropColumn('onlineSms_api_key');
        });
    }
};
