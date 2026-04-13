<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ParkingRecord;
use Carbon\Carbon;

class ParkingRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ParkingRecord::create([
            'vehicle_id' => 1,
            'place_id' => 1,
            'agent_id' => 3,
            'entry_time' => Carbon::now()->subHours(2),
            'exit_time' => Carbon::now(),
            'total_price' => 10.00,
        ]);
    }
}
