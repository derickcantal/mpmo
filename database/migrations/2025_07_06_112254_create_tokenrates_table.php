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
            $table->decimal('mpmototrx', 20, 6);
            $table->decimal('trxtompmo', 20, 6);
            $table->decimal('trxtophp', 20, 6);
            $table->decimal('phptotrx', 20, 6);
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
