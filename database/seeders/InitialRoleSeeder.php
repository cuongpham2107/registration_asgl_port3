<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class InitialRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id' => 1, 'name' => 'super_admin', 'guard_name' => 'web'],
            ['id' => 2, 'name' => 'panel_user', 'guard_name' => 'web'],
        ];

        foreach ($data as $item) {
            Role::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
