<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RentServiceSubtype;

class RentServiceSubtypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subtypes = [
            'Heavy Equipment Rental',
            'Construction Machinery Rental',
            'Concrete Mixer Rental',
            'Generator Rental',
            'Scaffolding Rental',
            'Lifting Equipment Rental',
            'Excavation Equipment Rental',
            'HVAC Equipment Rental',
            'Water Pump Rental',
            'Tools Rental',
            'Storage Container Rental',
            'Temporary Work Crew Rental',
        ];

        foreach ($subtypes as $subtype) {
            RentServiceSubtype::firstOrCreate([
                'name' => $subtype,
            ]);
        }
    }
}
