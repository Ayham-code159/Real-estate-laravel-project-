<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->string('name_ar')->nullable()->after('name_en');
        });

        DB::table('cities')->select('id', 'name')->orderBy('id')->get()->each(function ($row) {
            DB::table('cities')
                ->where('id', $row->id)
                ->update([
                    'name_en' => $row->name,
                    'name_ar' => $row->name,
                ]);
        });
    }

    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'name_ar']);
        });
    }
};
