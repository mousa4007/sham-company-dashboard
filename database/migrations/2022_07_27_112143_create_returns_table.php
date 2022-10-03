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
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('return');
            $table->string('reason');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('app_user_id')->constrained('app_users')->onDelete('cascade');
            $table->foreignId('agent_id')->nullable();
            $table->string('status')->default('ignored');
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
        Schema::dropIfExists('returns');
    }
};
