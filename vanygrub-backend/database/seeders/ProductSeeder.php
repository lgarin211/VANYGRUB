<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productsDataPath = base_path('../constants/productsData.json');
        $productsDetailedPath = base_path('../constants/productsDataDetailed.json');

        if (file_exists($productsDataPath) && file_exists($productsDetailedPath)) {
            $productsData = json_decode(file_get_contents($productsDataPath), true);
            $detailedData = json_decode(file_get_contents($productsDetailedPath), true);

            // Create index of detailed data by ID
            $detailedIndex = [];
            foreach ($detailedData['products'] as $detailed) {
                $detailedIndex[$detailed['id']] = $detailed;
            }

            foreach ($productsData['products'] as $product) {
                $category = \App\Models\Category::where('name', $product['category'])->first();
                $detailed = $detailedIndex[$product['id']] ?? null;

                \App\Models\Product::create([
                    'name' => $product['name'],
                    'slug' => \Illuminate\Support\Str::slug($product['name']),
                    'description' => $product['description'],
                    'detailed_description' => $detailed['detailDescription'] ?? null,
                    'price' => $product['originalPrice'],
                    'sale_price' => $detailed && isset($detailed['discount']) ? $product['originalPrice'] * (1 - (intval($detailed['discount']) / 100)) : null,
                    'sku' => 'VNY-' . str_pad($product['id'], 4, '0', STR_PAD_LEFT),
                    'stock_quantity' => rand(10, 100),
                    'manage_stock' => true,
                    'in_stock' => $product['inStock'] ?? true,
                    'status' => 'active',
                    'image' => $product['image'],
                    'main_image' => $detailed['mainImage'] ?? $product['image'],
                    'gallery' => $detailed['images'] ?? [$product['image']],
                    'weight' => isset($detailed['weight']) ? floatval(str_replace('g', '', $detailed['weight'])) : null,
                    'category_id' => $category ? $category->id : null,
                    'serial_number' => $product['serial'] ?? null,
                    'colors' => $detailed['colors'] ?? [],
                    'sizes' => $detailed['sizes'] ?? [],
                    'country_origin' => $detailed['countryOrigin'] ?? null,
                    'warranty' => $detailed['warranty'] ?? null,
                    'release_date' => isset($detailed['releaseDate']) ? \Carbon\Carbon::parse($detailed['releaseDate']) : now(),
                    'is_featured' => $product['featured'] ?? false
                ]);
            }
        }
    }
}
