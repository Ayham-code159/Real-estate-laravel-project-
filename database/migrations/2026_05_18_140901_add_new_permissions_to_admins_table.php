<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            if (! Schema::hasColumn('admins', 'can_manage_business_types')) {
                $table->boolean('can_manage_business_types')
                    ->default(false)
                    ->after('can_manage_business_accounts');
            }

            if (! Schema::hasColumn('admins', 'can_manage_categories')) {
                $table->boolean('can_manage_categories')
                    ->default(false)
                    ->after('can_manage_business_types');
            }

            if (! Schema::hasColumn('admins', 'can_manage_items')) {
                $table->boolean('can_manage_items')
                    ->default(false)
                    ->after('can_manage_categories');
            }

            if (! Schema::hasColumn('admins', 'can_manage_sliders')) {
                $table->boolean('can_manage_sliders')
                    ->default(false)
                    ->after('can_manage_items');
            }
        });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn([
                'can_manage_business_types',
                'can_manage_categories',
                'can_manage_items',
                'can_manage_sliders',
            ]);
        });
    }
};
