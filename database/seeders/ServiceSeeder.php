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
            'Electrical',
            'Plumbing',
            'Construction',
            'Finishing',
        ];

        foreach ($services as $service) {
            Service::firstOrCreate([
                'name' => $service,
            ]);
        }
    }
}
