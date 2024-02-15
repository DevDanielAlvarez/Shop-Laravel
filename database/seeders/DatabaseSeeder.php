<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        if (empty(User::where('email', 'ddd@ddd.com')->get()->toArray())) {
            User::factory()->create([
                'name' => 'ddd',
                'email' => 'ddd@ddd.com',
            ]);
        }

        $this->call([
            //priority 1
            UserSeeder::class,
            ColorSeeder::class,
            CategorySeeder::class,

            //priority 2
            SupplierSeeder::class,
            ProductSeeder::class,

            //priority 3
            ColorProductSeeder::class,

        ]);
    }
}
