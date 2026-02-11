<?php

namespace Database\Seeders;

use App\Models\Gateway;
use Illuminate\Database\Seeder;

class GatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id' => 1, 'name' => 'Cổng 1'],
            ['id' => 2, 'name' => 'Cổng 2 '],
            ['id' => 3, 'name' => 'Cổng 3'],
        ];

        foreach ($data as $item) {
            Gateway::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
