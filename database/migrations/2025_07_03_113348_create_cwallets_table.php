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
            $table->string('cwaddress')->unique();
            $table->string('qrbsccwaddress')->nullable(); 
            $table->string('bsccwaddress')->nullable();
            $table->string('qrwallcode')->nullable();
            $table->string('wallcode')->nullable();
            $table->string('ownercwaddress')->nullable();
            $table->string('ownerqrcwaddress')->nullable();
            $table->decimal('mpmobal', 20, 6)->default(0);
            $table->decimal('trxbal', 20, 6)->default(0);
            $table->decimal('usdtbal', 20, 6)->default(0);
            $table->decimal('totalbal', 20, 6)->default(0);
            $table->decimal('dailyin', 20, 6)->default(0);
            $table->decimal('availbal', 20, 6)->default(0);
            $table->integer('pets')->nullable();
            $table->string('notes')->nullable();
            $table->unsignedInteger('userid');
            $table->foreign('userid')->references('userid')->on('users')->onDelete('cascade');
            $table->text('private_key'); // encrypt before saving!
            $table->string('public_key');
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
