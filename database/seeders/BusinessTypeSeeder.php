<?php

namespace Database\Seeders;

use App\Models\BusinessType;
use Illuminate\Database\Seeder;

class BusinessTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Contractor',
            'Supplier',
            'Service Provider',
            'Distributor',
        ];

        foreach ($types as $type) {
            BusinessType::firstOrCreate([
                'name' => $type,
            ]);
        }
    }
}
