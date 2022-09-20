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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image_url');
            $table->string('image_id');
            $table->double('buy_price')->nullable();
            $table->double('sell_price')->nullable();
            $table->string('description');
            $table->tinyInteger('currency');
            $table->integer('arrangement');
            $table->boolean('is_direct')->nullable();
            $table->boolean('is_transfer')->nullable();
            $table->boolean('only_number')->nullable();
            $table->string('hint_message')->nullable();
            $table->string('status')->default('active');
            $table->string('available')->default('available');
            $table->string('country_number')->nullable();
            $table->string('service_code')->nullable();
            $table->string('web_api')->nullable();
            $table->string('smsActivate_api_key')->default('87f928c1639b2be22f8f3AAcd052A334');
            $table->string('vakSms_api_key')->default('938f785b9f024a2aa74d6d354ed0dbca');
            $table->string('secondLine_api_key')->default('d0ccfaa3ae5471fb24ddaf00b1cdf458');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
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
        Schema::dropIfExists('products');
    }
};
