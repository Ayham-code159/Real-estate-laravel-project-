<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pending_user_registrations', function (Blueprint $table) {
            $table->id();

            $table->string('first_name');
            $table->string('last_name');
            $table->string('identifier');
            $table->string('identifier_type');
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable()->index();
            $table->string('password');

            $table->string('token', 100)->unique();
            $table->timestamp('expires_at');
            $table->timestamp('last_sent_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending_user_registrations');
    }
};
