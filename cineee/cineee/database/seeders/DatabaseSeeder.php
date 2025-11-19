<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ],
        );

        User::updateOrCreate(
            ['email' => 'cliente@example.com'],
            [
                'name' => 'Cliente Demo',
                'role' => 'usuario',
                'password' => bcrypt('12345678'),
            ],
        );

        User::updateOrCreate(
            ['email' => 'israel@outlook.com'],
            [
                'name' => 'Admin',
                'role' => 'admin',
                'password' => bcrypt('12345678'),
            ],
        );
    }
}
