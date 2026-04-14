<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('business_accounts', function (Blueprint $table) {
            $table->string('business_name_en')->nullable()->after('business_name');
            $table->string('business_name_ar')->nullable()->after('business_name_en');
        });

        DB::table('business_accounts')
            ->select('id', 'business_name')
            ->orderBy('id')
            ->get()
            ->each(function ($row) {
                DB::table('business_accounts')
                    ->where('id', $row->id)
                    ->update([
                        'business_name_en' => $row->business_name,
                        'business_name_ar' => null,
                    ]);
            });
    }

    public function down(): void
    {
        Schema::table('business_accounts', function (Blueprint $table) {
            $table->dropColumn([
                'business_name_en',
                'business_name_ar',
            ]);
        });
    }
};
