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
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('txnid');
            $table->string('tokenid');
            $table->string('tokenname');
            $table->string('txnhash');
            $table->string('txnimg');
            $table->string('txntype');
            $table->string('addresssend');
            $table->string('addressreceive');
            $table->decimal('amount', 20, 6);
            $table->decimal('amountvalue', 20, 6);
            $table->decimal('amountfee', 20, 6);
            $table->unsignedInteger('cwid');
            $table->foreign('cwid')->references('cwid')->on('cwallets')->onDelete('cascade');
            $table->string('fullname')->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
