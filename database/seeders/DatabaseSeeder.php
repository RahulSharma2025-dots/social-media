<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'user_type' => 'normal',
            'category' => 'test',
            'bio' => 'test',
            'is_admin' => false,
            'is_verified' => true,
            'is_banned' => false,
        ]);

        $this->call([
            TopicSeeder::class,
            AdminSeeder::class,
            // Add other seeders here
        ]);
    }
}
