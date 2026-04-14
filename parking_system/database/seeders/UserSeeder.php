<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role_id' => 1
        ]);

        for ($i = 0; $i <= 10; $i++) {
            User::create([
                'name' => 'User',
                'email' => 'user@test.com',
                'password' => Hash::make('password'),
                'role_id' => 2
            ]);
        }

        for ($i = 0; $i <= 10; $i++) {
            User::create([
                'name' => 'Agent',
                'email' => 'agent@test.com',
                'password' => Hash::make('password'),
                'role_id' => 3
            ]);
        }
    }
}
