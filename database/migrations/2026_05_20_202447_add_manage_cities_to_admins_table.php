<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {

            if (! Schema::hasColumn('admins', 'can_manage_cities')) {
                $table->boolean('can_manage_cities')
                    ->default(false)
                    ->after('can_manage_sliders');
            }

        });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {

            $table->dropColumn([
                'can_manage_cities',
            ]);

        });
    }
};
