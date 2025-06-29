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
        Schema::create('temp_users', function (Blueprint $table) {
            $table->increments('userid');
            $table->string('avatar');
            $table->string('username');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('trxaddress')->nullable();
            $table->string('usdtaddress')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('email')->unique();
            $table->string('mobile_primary')->nullable();
            $table->string('mobile_secondary')->nullable();
            $table->string('homeno')->nullable();
            $table->string('rnotes')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->string('accesstype');
            $table->dateTime('timerecorded');
            $table->string('created_by');
            $table->string('updated_by');
            $table->integer('mod');
            $table->string('copied')->nullable();
            $table->string('status');
        });

       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_users');
        
    }
};
