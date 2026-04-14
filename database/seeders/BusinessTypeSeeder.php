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
            [
                'name_en' => 'Contractor',
                'name_ar' => 'مقاول',
            ],
            [
                'name_en' => 'Supplier',
                'name_ar' => 'مورد',
            ],
            [
                'name_en' => 'Service Provider',
                'name_ar' => 'مزود خدمات',
            ],
            [
                'name_en' => 'Distributor',
                'name_ar' => 'موزع',
            ],
        ];

        foreach ($types as $type) {
            BusinessType::updateOrCreate(
                ['name_en' => $type['name_en']],
                [
                    'name' => $type['name_en'],
                    'name_en' => $type['name_en'],
                    'name_ar' => $type['name_ar'],
                ]
            );
        }
    }
}
