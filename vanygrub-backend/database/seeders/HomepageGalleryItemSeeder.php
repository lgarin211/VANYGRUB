<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageGalleryItem;

class HomepageGalleryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $galleryItems = [
            [
                'title' => 'Vany Songket',
                'image' => 'https://images.unsplash.com/photo-1546548970-71785318a17b?w=600&h=800&fit=crop',
                'description' => 'Koleksi songket tradisional dengan desain modern yang memadukan kearifan lokal dengan gaya kontemporer. Dibuat dengan benang emas dan perak berkualitas tinggi.',
                'target' => '/',
                'category' => 'Traditional Fashion',
                'sort_order' => 1,
                'is_active' => true
            ],
            [
                'title' => 'Vny Toba Shoes',
                'image' => 'https://vanyadmin.progesio.my.id/storage/temp/01KBA3TQSB8X78WRK1YP7E9JT0.png',
                'description' => 'Sepatu berkualitas tinggi dengan design yang nyaman dan gaya yang elegan untuk aktivitas sehari-hari. Terbuat dari kulit asli premium.',
                'target' => '/vny',
                'category' => 'Footwear',
                'sort_order' => 2,
                'is_active' => true
            ],
            [
                'title' => 'Vany Villa',
                'image' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=600&h=800&fit=crop',
                'description' => 'Villa mewah dengan pemandangan indah dan fasilitas lengkap untuk liburan yang tak terlupakan. Dilengkapi dengan kolam renang pribadi.',
                'target' => '/',
                'category' => 'Hospitality',
                'sort_order' => 3,
                'is_active' => true
            ],
            [
                'title' => 'Vany Apartement',
                'image' => 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=600&h=800&fit=crop',
                'description' => 'Apartemen modern dengan lokasi strategis dan fasilitas premium untuk hunian yang nyaman. Dilengkapi dengan gym dan rooftop garden.',
                'target' => '/',
                'category' => 'Real Estate',
                'sort_order' => 4,
                'is_active' => true
            ],
            [
                'title' => 'Vany Shalon',
                'image' => 'https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=600&h=800&fit=crop',
                'description' => 'Salon kecantikan dengan layanan profesional dan perawatan terbaik untuk penampilan yang memukau. Treatment dengan produk premium.',
                'target' => '/',
                'category' => 'Beauty & Wellness',
                'sort_order' => 5,
                'is_active' => true
            ]
        ];

        foreach ($galleryItems as $item) {
            HomepageGalleryItem::updateOrCreate(
                ['title' => $item['title']],
                $item
            );
        }

        $this->command->info('Homepage gallery items seeded successfully!');
    }
}
