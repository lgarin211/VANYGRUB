<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get unique categories from products data
        $productsDataPath = base_path('../constants/productsData.json');

        if (file_exists($productsDataPath)) {
            $productsData = json_decode(file_get_contents($productsDataPath), true);
            $categories = [];

            foreach ($productsData['products'] as $product) {
                $categoryName = $product['category'];
                if (!in_array($categoryName, $categories)) {
                    $categories[] = $categoryName;
                }
            }

            foreach ($categories as $index => $categoryName) {
                \App\Models\Category::create([
                    'name' => $categoryName,
                    'slug' => \Illuminate\Support\Str::slug($categoryName),
                    'description' => 'Category for ' . $categoryName,
                    'is_active' => true,
                    'sort_order' => $index + 1
                ]);
            }
        }
    }
}
