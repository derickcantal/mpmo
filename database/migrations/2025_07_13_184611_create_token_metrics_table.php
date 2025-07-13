<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('token_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('symbol')->unique();
            $table->unsignedBigInteger('max_supply');
            $table->unsignedBigInteger('total_supply');
            $table->unsignedBigInteger('burned_supply')->default(0);
            $table->unsignedBigInteger('locked_supply')->default(0);
            $table->unsignedBigInteger('treasury_balance')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_metrics');
    }
};
