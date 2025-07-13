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
            $table->string('txnimg')->nullable();
            $table->string('txntype');
            $table->string('addresssend');
            $table->string('addressreceive');
            $table->unsignedBigInteger('trx_amount')->nullable();
            $table->unsignedBigInteger('mpmo_gross')->nullable();
            $table->unsignedBigInteger('mpmo_fee')->nullable();
            $table->unsignedBigInteger('mpmo_net')->nullable();
            $table->enum('type', ['conversion','burn','buyback','purchase','referral']);
            $table->json('meta')->nullable();
            $table->unsignedInteger('cwid');
            $table->foreign('cwid')->references('cwid')->on('cwallets')->onDelete('cascade');
            $table->unsignedInteger('userid');
            $table->foreign('userid')->references('userid')->on('users')->onDelete('cascade');
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
