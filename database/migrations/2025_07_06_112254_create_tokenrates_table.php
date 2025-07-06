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
        Schema::create('tokenrates', function (Blueprint $table) {
            $table->increments('trid');
            $table->string('trdescription');
            $table->decimal('mpmototrx', $precision = 8, $scale = 2);
            $table->decimal('trxtompmo', $precision = 8, $scale = 2);
            $table->decimal('trxtophp', $precision = 8, $scale = 2);
            $table->decimal('phptotrx', $precision = 8, $scale = 2);
            $table->dateTime('timerecorded');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->integer('mod');
            $table->string('copied')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokenrates');
    }
};
