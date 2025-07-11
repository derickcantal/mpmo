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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('userid');
            $table->string('avatar');
            $table->string('rfid');
            $table->string('username');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->integer('cwid')->nullable();
            $table->string('cwaddress')->nullable();
            $table->string('qrcwaddress')->nullable();
            $table->string('qrbsccwaddress')->nullable(); 
            $table->string('bsccwaddress')->nullable();
            $table->string('ownercwaddress')->nullable();
            $table->string('ownerqrcwaddress')->nullable();
            $table->decimal('mpmobal', 20, 6);
            $table->decimal('trxbal', 20, 6);
            $table->decimal('usdtbal', 20, 6);
            $table->decimal('totalbal', 20, 6);
            $table->decimal('dailyin', 20, 6);
            $table->decimal('availbal', 20, 6);
            $table->integer('pets')->nullable();
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
            $table->string('role');
            $table->dateTime('timerecorded');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->integer('mod');
            $table->string('copied')->nullable();
            $table->string('walletstatus')->nullable();
            $table->string('rfidby')->nullable();
            $table->string('status');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
