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
        Schema::create('vpn_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('public_key');
            $table->text('private_key');
            $table->longblob('qr_code')->nullable();
            $table->string('address');       // e.g. 10.0.0.2/32
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vpn_clients');
    }
};
