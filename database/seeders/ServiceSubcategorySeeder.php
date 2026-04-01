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
                'Wiring',
                'Generator Setup',
                'Lighting Installation',
                'Electrical Maintenance',
            ],
            'Plumbing' => [
                'Pipe Installation',
                'Water Heater Setup',
                'Leak Repair',
                'Drain Maintenance',
            ],
            'Construction' => [
                'Concrete Work',
                'Block Work',
                'Steel Installation',
                'Tool Supply',
            ],
            'Finishing' => [
                'House Painting',
                'Gypsum Work',
                'Tile Installation',
                'Interior Finishing',
            ],
        ];

        foreach ($subcategories as $serviceName => $items) {
            $service = Service::where('name', $serviceName)->first();

            if (! $service) {
                continue;
            }

            foreach ($items as $item) {
                ServiceSubcategory::firstOrCreate([
                    'service_id' => $service->id,
                    'name' => $item,
                ]);
            }
        }
    }
}
