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
            $table->id();
            $table->string('img')->nullable();
            $table->string('username');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('otp_code')->nullable();
            $table->string('password');
            $table->boolean('status')->default(0);
            $table->timestamp('otp_expired')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
