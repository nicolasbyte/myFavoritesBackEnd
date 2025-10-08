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

        User::factory()->create([
            'firstName' => 'Test',
            'firstLastName' => 'User',
            'identification' => '1234567890',
            'email' => config('services.test_user.email', 'test@example.com'),
            'password' => config('services.test_user.password', 'password123'),
        ]);
    }
}
