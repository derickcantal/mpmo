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
            $table->decimal('max_supply',24, 8);
            $table->decimal('total_supply',24, 8);
            $table->decimal('burned_supply',24, 8)->default(0);
            $table->decimal('locked_supply',24, 8)->default(0);
            $table->decimal('treasury_balance',24, 8)->default(0);
            $table->decimal('price',24, 8)->default(0);
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
