<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Parking;

class ParkingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i <= 5; $i++) {
            Parking::create([
                'name' => 'Parking1',
                'address' => '@adress1',
                'total_places' => 10,
                'opening_hours' => '08:00 - 22:00',
                'email' => 'contact@parking1.com',
                'phone' => '0666666666',
                'price_car' => 5.00,
                'price_motorcycle' => 3.00,
            ]);
        }

        for ($i = 0; $i <= 5; $i++) {
            Parking::create([
                'name' => 'Parking2',
                'address' => '@adress2',
                'total_places' => 10,
                'opening_hours' => '08:00 - 22:00',
                'email' => 'contact@parking2.com',
                'phone' => '0666666666',
                'price_car' => 5.00,
                'price_motorcycle' => 3.00,
            ]);
        }

        for ($i = 0; $i <= 5; $i++) {
            Parking::create([
                'name' => 'Parking3',
                'address' => '@adress2',
                'total_places' => 10,
                'opening_hours' => '08:00 - 22:00',
                'email' => 'contact@parking2.com',
                'phone' => '0666666666',
                'price_car' => 5.00,
                'price_motorcycle' => 3.00,
            ]);
        }
    }
}
