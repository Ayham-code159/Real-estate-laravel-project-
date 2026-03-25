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
        Schema::table('admins', function (Blueprint $table) {
            $table->boolean('is_super_admin')->default(false)->after('password');
            $table->boolean('can_manage_users')->default(false)->after('is_super_admin');
            $table->boolean('can_manage_business_accounts')->default(false)->after('can_manage_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn([
                'is_super_admin',
                'can_manage_users',
                'can_manage_business_accounts',
            ]);
        });
    }
};
