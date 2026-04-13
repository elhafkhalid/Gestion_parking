<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vehicle::create([
            'plate_number' => 'AA-123-BB',
            'type' => 'Car',
            'color' => 'Black'
        ]);

        Vehicle::create([
            'plate_number' => 'CC-456-DD',
            'type' => 'Motorcycle',
            'color' => 'Red'
        ]);
    }
}
