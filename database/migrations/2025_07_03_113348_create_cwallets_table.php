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
        Schema::create('cwallets', function (Blueprint $table) {
            $table->increments('cwid');
            $table->string('qrcwaddress')->nullable(); 
            $table->string('cwaddress');
            $table->string('qrbsccwaddress')->nullable(); 
            $table->string('bsccwaddress');
            $table->string('qrwallcode')->nullable();
            $table->string('wallcode');
            $table->string('notes')->nullable();
            $table->string('userid')->nullable();
            $table->string('fullname')->nullable();
            $table->dateTime('timerecorded');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->integer('mod');
            $table->string('copied')->nullable();
            $table->string('walletstatus')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cwallets');
    }
};
