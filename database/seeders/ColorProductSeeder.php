<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ColorProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Color::factory(5)->create()->each(function (Color $color) {

            Product::factory(5)->create()->each(function (Product $product) use ($color) {

                $product->colors()->attach($color, ['quantity' => 4]);
            });

        });

    }
}
