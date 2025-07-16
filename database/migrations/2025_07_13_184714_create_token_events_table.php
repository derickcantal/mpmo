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
        Schema::create('token_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('token_metric_id')->constrained();
            $table->unsignedInteger('userid');
            $table->foreign('userid')->references('userid')->on('users')->onDelete('cascade');
            $table->enum('type', ['mint','burn','conversion','purchase','referral_fee','buyback','airdrop']);
            $table->decimal('amount',24, 8);
            $table->decimal('fee',24, 8)->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_events');
    }
};
