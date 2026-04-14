<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name_en' => 'Electrical',
                'name_ar' => 'كهرباء',
            ],
            [
                'name_en' => 'Plumbing',
                'name_ar' => 'سباكة',
            ],
            [
                'name_en' => 'Construction',
                'name_ar' => 'إنشاءات',
            ],
            [
                'name_en' => 'Finishing',
                'name_ar' => 'تشطيبات',
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['name_en' => $service['name_en']],
                [
                    'name' => $service['name_en'],
                    'name_en' => $service['name_en'],
                    'name_ar' => $service['name_ar'],
                ]
            );
        }
    }
}
