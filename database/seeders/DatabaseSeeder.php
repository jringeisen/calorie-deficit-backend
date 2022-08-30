<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\ConsumedFood;
use App\Models\CaloriesBurned;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();

        if (!$user) {
            \App\Models\User::factory()->create([
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => 'test@example.com',
            ]);
        }

        for ($i = 1; $i <= 5; $i++) {
            CaloriesBurned::factory()->create([
                'user_id' => $user->id,
                'created_at' => now()->subDays($i),
                'updated_at' => now()->subDays($i),
            ]);
        }

        for ($i = 0; $i <= 5; $i++) {
            ConsumedFood::factory()->count(5)->create([
                'user_id' => $user->id,
                'created_at' => now()->subDays($i),
                'updated_at' => now()->subDays($i),
            ]);
        }
    }
}
