<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            'Damascus',
            'Aleppo',
            'Homs',
            'Hama',
            'Latakia',
            'Tartus',
            'Idlib',
            'Daraa',
            'Deir ez-Zor',
            'Raqqa',
        ];

        foreach ($cities as $city) {
            City::firstOrCreate([
                'name' => $city,
            ]);
        }
    }
}
