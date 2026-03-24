<?php

namespace Database\Seeders;

use App\Models\BusinessType;
use Illuminate\Database\Seeder;

class BusinessTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Building Materials Supplier',
            'Tools & Equipment Supplier',
            'Wood & Carpentry Supplier',
            'Iron & Steel Supplier',
            'Cement & Concrete Supplier',
            'Electrical Materials Supplier',
            'Plumbing Materials Supplier',
            'Paint & Finishing Supplier',
            'Aluminum & Glass Supplier',
            'Tiles & Flooring Supplier',
            'Doors & Windows Supplier',
            'Furniture Supplier',
            'Interior Design Services',
            'Architecture & Engineering Services',
            'Construction Contractor',
            'Maintenance & Repair Services',
            'Heavy Equipment Rental',
            'Transport & Delivery Services',
            'HVAC Services',
            'Sanitary Ware Supplier',
        ];

        foreach ($types as $type) {
            BusinessType::firstOrCreate([
                'name' => $type,
            ]);
        }
    }
}
