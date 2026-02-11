<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'admin',
                'password' => '$2y$12$ArNlRF.uDN326Q50N9CA6.695RFJFjh03QepBOLVsZjsROP6YXNg2', // Original hash
                'username' => 'admin@admin.com',
            ]
        );

        $admin->assignRole('super_admin');
    }
}
