<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            ['name_en' => 'Damascus', 'name_ar' => 'دمشق'],
            ['name_en' => 'Aleppo', 'name_ar' => 'حلب'],
            ['name_en' => 'Homs', 'name_ar' => 'حمص'],
            ['name_en' => 'Hama', 'name_ar' => 'حماة'],
            ['name_en' => 'Latakia', 'name_ar' => 'اللاذقية'],
            ['name_en' => 'Tartus', 'name_ar' => 'طرطوس'],
            ['name_en' => 'Idlib', 'name_ar' => 'إدلب'],
            ['name_en' => 'Daraa', 'name_ar' => 'درعا'],
            ['name_en' => 'Deir ez-Zor', 'name_ar' => 'دير الزور'],
            ['name_en' => 'Raqqa', 'name_ar' => 'الرقة'],
        ];

        foreach ($cities as $city) {
            City::updateOrCreate(
                ['name_en' => $city['name_en']],
                [
                    'name' => $city['name_en'], // keep synced
                    'name_en' => $city['name_en'],
                    'name_ar' => $city['name_ar'],
                ]
            );
        }
    }
}
