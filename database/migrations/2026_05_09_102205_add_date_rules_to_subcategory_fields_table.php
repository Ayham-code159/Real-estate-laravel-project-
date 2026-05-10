<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('subcategory_fields', function (Blueprint $table) {
            $table->date('min_date')->nullable()->after('max_value');
            $table->date('max_date')->nullable()->after('min_date');
        });
    }

    public function down(): void
    {
        Schema::table('subcategory_fields', function (Blueprint $table) {
            $table->dropColumn(['min_date', 'max_date']);
        });
    }
};
