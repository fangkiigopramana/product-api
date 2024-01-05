<?php

namespace Database\Seeders;

use App\Models\ProductAsset;
use Illuminate\Database\Seeder;

class ProductAssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=0; $i < 10; $i++) { 
            ProductAsset::create([
                'product_id' => random_int(1,5),
                'image' => fake()->word() . '.jpg'
            ]);
        }
    }
}
