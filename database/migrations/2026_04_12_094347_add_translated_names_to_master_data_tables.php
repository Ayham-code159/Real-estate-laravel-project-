<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('business_types', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->string('name_ar')->nullable()->after('name_en');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->string('name_ar')->nullable()->after('name_en');
        });

        Schema::table('service_subcategories', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->string('name_ar')->nullable()->after('name_en');
        });

        DB::table('business_types')->select('id', 'name')->orderBy('id')->get()->each(function ($row) {
            DB::table('business_types')
                ->where('id', $row->id)
                ->update([
                    'name_en' => $row->name,
                    'name_ar' => $row->name,
                ]);
        });

        DB::table('services')->select('id', 'name')->orderBy('id')->get()->each(function ($row) {
            DB::table('services')
                ->where('id', $row->id)
                ->update([
                    'name_en' => $row->name,
                    'name_ar' => $row->name,
                ]);
        });

        DB::table('service_subcategories')->select('id', 'name')->orderBy('id')->get()->each(function ($row) {
            DB::table('service_subcategories')
                ->where('id', $row->id)
                ->update([
                    'name_en' => $row->name,
                    'name_ar' => $row->name,
                ]);
        });
    }

    public function down(): void
    {
        Schema::table('service_subcategories', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'name_ar']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'name_ar']);
        });

        Schema::table('business_types', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'name_ar']);
        });
    }
};
