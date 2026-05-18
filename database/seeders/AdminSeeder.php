<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Admin@123'),

                'is_super_admin' => true,
                'can_manage_users' => true,
                'can_manage_business_accounts' => true,
                'can_manage_business_types' => true,
                'can_manage_categories' => true,
                'can_manage_items' => true,
                'can_manage_sliders' => true,
            ]
        );
    }
}
