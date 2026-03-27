<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SellServiceSubtype;

class SellServiceSubtypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subtypes = [
            'Tools & Equipment Sale',
            'Building Materials Sale',
            'Paint & Finishing Materials Sale',
            'Wood & Carpentry Materials Sale',
            'Iron & Steel Materials Sale',
            'Cement & Concrete Materials Sale',
            'Electrical Materials Sale',
            'Plumbing Materials Sale',
            'Aluminum & Glass Materials Sale',
            'Tiles & Flooring Sale',
            'Doors & Windows Sale',
            'Furniture Sale',
            'Sanitary Ware Sale',
            'House Painting',
            'Interior Design Work',
            'Architecture & Engineering Work',
            'Construction Contracting',
            'Maintenance & Repair Work',
            'HVAC Work',
            'Plumbing Work',
            'Electrical Work',
            'Carpentry Work',
            'Aluminum & Glass Installation',
            'Flooring Installation',
            'Door & Window Installation',
        ];

        foreach ($subtypes as $subtype) {
            SellServiceSubtype::firstOrCreate([
                'name' => $subtype,
            ]);
        }
    }
}
