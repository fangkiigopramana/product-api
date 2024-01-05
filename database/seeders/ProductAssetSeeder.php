<?php

namespace Database\Seeders;

use App\Models\ProductAssest;
use App\Models\ProductAsset;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Testing\Fakes\Fake;

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
                'image' => fake()->name()
            ]);
        }
    }
}
