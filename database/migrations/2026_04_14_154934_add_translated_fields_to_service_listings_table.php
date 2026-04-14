<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_listings', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->string('title_ar')->nullable()->after('title_en');
            $table->text('description_en')->nullable()->after('description');
            $table->text('description_ar')->nullable()->after('description_en');
        });

        DB::table('service_listings')
            ->select('id', 'title', 'description')
            ->orderBy('id')
            ->get()
            ->each(function ($row) {
                DB::table('service_listings')
                    ->where('id', $row->id)
                    ->update([
                        'title_en' => $row->title,
                        'title_ar' => null,
                        'description_en' => $row->description,
                        'description_ar' => null,
                    ]);
            });
    }

    public function down(): void
    {
        Schema::table('service_listings', function (Blueprint $table) {
            $table->dropColumn([
                'title_en',
                'title_ar',
                'description_en',
                'description_ar',
            ]);
        });
    }
};
