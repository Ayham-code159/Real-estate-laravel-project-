<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceSubcategory;
use Illuminate\Database\Seeder;

class ServiceSubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcategories = [
            'Electrical' => [
                [
                    'name_en' => 'Wiring',
                    'name_ar' => 'تمديدات كهربائية',
                ],
                [
                    'name_en' => 'Generator Setup',
                    'name_ar' => 'تركيب مولدات',
                ],
                [
                    'name_en' => 'Lighting Installation',
                    'name_ar' => 'تركيب إنارة',
                ],
                [
                    'name_en' => 'Electrical Maintenance',
                    'name_ar' => 'صيانة كهربائية',
                ],
            ],
            'Plumbing' => [
                [
                    'name_en' => 'Pipe Installation',
                    'name_ar' => 'تركيب أنابيب',
                ],
                [
                    'name_en' => 'Water Heater Setup',
                    'name_ar' => 'تركيب سخانات مياه',
                ],
                [
                    'name_en' => 'Leak Repair',
                    'name_ar' => 'إصلاح تسريبات',
                ],
                [
                    'name_en' => 'Drain Maintenance',
                    'name_ar' => 'صيانة المصارف',
                ],
            ],
            'Construction' => [
                [
                    'name_en' => 'Concrete Work',
                    'name_ar' => 'أعمال خرسانية',
                ],
                [
                    'name_en' => 'Block Work',
                    'name_ar' => 'أعمال بلوك',
                ],
                [
                    'name_en' => 'Steel Installation',
                    'name_ar' => 'تركيب حديد',
                ],
                [
                    'name_en' => 'Tool Supply',
                    'name_ar' => 'توريد أدوات',
                ],
            ],
            'Finishing' => [
                [
                    'name_en' => 'House Painting',
                    'name_ar' => 'دهان منازل',
                ],
                [
                    'name_en' => 'Gypsum Work',
                    'name_ar' => 'أعمال جبصين',
                ],
                [
                    'name_en' => 'Tile Installation',
                    'name_ar' => 'تركيب بلاط',
                ],
                [
                    'name_en' => 'Interior Finishing',
                    'name_ar' => 'تشطيبات داخلية',
                ],
            ],
        ];

        foreach ($subcategories as $serviceNameEn => $items) {
            $service = Service::where('name_en', $serviceNameEn)->first();

            if (! $service) {
                continue;
            }

            foreach ($items as $item) {
                ServiceSubcategory::updateOrCreate(
                    [
                        'service_id' => $service->id,
                        'name_en' => $item['name_en'],
                    ],
                    [
                        'name' => $item['name_en'],
                        'name_en' => $item['name_en'],
                        'name_ar' => $item['name_ar'],
                    ]
                );
            }
        }
    }
}
