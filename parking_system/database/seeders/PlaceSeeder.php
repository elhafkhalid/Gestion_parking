<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Place;

class PlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Place::create([
                'parking_id' => 1,
                'number' => 'P' . $i,
                'is_occupied' => false,
            ]);
        }

        for ($i = 1; $i <= 5; $i++) {
            Place::create([
                'parking_id' => 2,
                'number' => 'P' . $i,
                'is_occupied' => false,
            ]);
        }

        for ($i = 1; $i <= 15; $i++) {
            Place::create([
                'parking_id' => 3,
                'number' => 'P' . $i,
                'is_occupied' => true,
            ]);
        }
    }
}
