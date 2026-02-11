<?php

namespace Database\Seeders;

use App\Models\LoadCapacity;
use Illuminate\Database\Seeder;

class LoadCapacitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'name' => "Xe chở container 40'-45', xe trên 10 tấn, xe rơ móoc",
                'daytime_price' => 100000,
                'nighttime_price' => 200000,
            ],
            [
                'id' => 2,
                'name' => 'Xe ô tô 17-29 chỗ, xe tải trên 3,5 tấn đến 7 tấn',
                'daytime_price' => 60000,
                'nighttime_price' => 120000,
            ],
        ];

        foreach ($data as $item) {
            LoadCapacity::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
