<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('business_accounts', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('business_name_ar');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('location_label')->nullable()->after('longitude');
        });

        Schema::table('service_listings', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('price_syp');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('location_label')->nullable()->after('longitude');
        });
    }

    public function down(): void
    {
        Schema::table('business_accounts', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'location_label']);
        });

        Schema::table('service_listings', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'location_label']);
        });
    }
};
