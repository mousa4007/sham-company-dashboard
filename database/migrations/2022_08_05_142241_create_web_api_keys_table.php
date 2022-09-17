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
        Schema::create('web_api_keys', function (Blueprint $table) {
            $table->id();
            $table->string('smsActivate_api_key')->default('87f928c1639b2be22f8f3AAcd052A334');
            $table->string('vakSms_api_key')->default('938f785b9f024a2aa74d6d354ed0dbca');
            $table->string('secondLine_api_key')->default('d0ccfaa3ae5471fb24ddaf00b1cdf458');
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
        Schema::dropIfExists('web_api_keys');
    }
};
