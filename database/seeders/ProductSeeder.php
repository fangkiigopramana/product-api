<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'category_id' => 1,
                'name' => "Logitech H111 Headset Stereo Single Jack 3.5mm",
            ],
            [
                'category_id' => 1,
                'name' => "Philips Rice Cooker - Inner Pot 2L Bakuhanseki - HD3110/33",
            ],
            [
                'category_id' => 4,
                'name' => "Iphone 12 64Gb/128Gb/256Gb Garansi Resmi IBOX/TAM - Hitam, 64Gb",
            ],
            [
                'category_id' => 5,
                'name' => "Papan alat bantu Push Up Rack Board Fitness Workout Gym",
            ],
            [
                'category_id' => 2,
                'name' => "Jim Joker - Sandal Slide Kulit Pria Bold 2S Hitam - Hitam"
            ],
        ];

        foreach ($products as $pro) {
            Product::create([
                'category_id' => $pro['category_id'],
                'name' => $pro['name'],
                'slug' => Str::slug($pro['name']),
                'price' => random_int(1000,1000000)
            ]);
        }
    }
}
