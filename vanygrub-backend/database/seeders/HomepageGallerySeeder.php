<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageGalleryItem;

class HomepageGallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $galleryItems = [
            [
                'id' => 1,
                'title' => 'Vany Songket',
                'image' => 'https://images.unsplash.com/photo-1546548970-71785318a17b?w=600&h=800&fit=crop',
                'description' => 'Koleksi songket tradisional dengan desain modern yang memadukan kearifan lokal dengan gaya kontemporer. Dibuat dengan benang emas dan perak berkualitas tinggi.',
                'target' => '/',
                'category' => 'Traditional Fashion',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'id' => 2,
                'title' => 'Vny Toba Shoes',
                'image' => 'https://vanygroup.id/storage/temp/01KBA3TQSB8X78WRK1YP7E9JT0.png',
                'description' => 'Sepatu berkualitas tinggi dengan design yang nyaman dan gaya yang elegan untuk aktivitas sehari-hari. Terbuat dari kulit asli premium.',
                'target' => '/vny',
                'category' => 'Footwear',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'id' => 3,
                'title' => 'Vany Villa',
                'image' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=600&h=800&fit=crop',
                'description' => 'Villa mewah dengan pemandangan indah dan fasilitas lengkap untuk liburan yang tak terlupakan. Dilengkapi dengan kolam renang pribadi.',
                'target' => '/',
                'category' => 'Hospitality',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'id' => 4,
                'title' => 'Vany Apartement',
                'image' => 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=600&h=800&fit=crop',
                'description' => 'Apartemen modern dengan lokasi strategis dan fasilitas premium untuk hunian yang nyaman. Dilengkapi dengan gym dan rooftop garden.',
                'target' => '/',
                'category' => 'Real Estate',
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'id' => 5,
                'title' => 'Vany Shalon',
                'image' => 'https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=600&h=800&fit=crop',
                'description' => 'Salon kecantikan dengan layanan profesional dan perawatan terbaik untuk penampilan yang memukau. Treatment dengan produk premium.',
                'target' => '/',
                'category' => 'Beauty & Wellness',
                'is_active' => true,
                'sort_order' => 5
            ],
            [
                'id' => 6,
                'title' => 'Vany Restaurant',
                'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=600&h=800&fit=crop',
                'description' => 'Restoran dengan cita rasa autentik dan suasana yang nyaman untuk pengalaman kuliner yang tak terlupakan.',
                'target' => '/',
                'category' => 'Culinary',
                'is_active' => true,
                'sort_order' => 6
            ],
            [
                'id' => 7,
                'title' => 'Vany Hotel',
                'image' => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=600&h=800&fit=crop',
                'description' => 'Hotel bintang lima dengan layanan premium dan fasilitas mewah untuk pengalaman menginap yang istimewa.',
                'target' => '/',
                'category' => 'Hospitality',
                'is_active' => true,
                'sort_order' => 7
            ],
            [
                'id' => 8,
                'title' => 'Vany Travel',
                'image' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=600&h=800&fit=crop',
                'description' => 'Layanan travel dan wisata dengan paket liburan terbaik ke destinasi menawan di seluruh dunia.',
                'target' => '/',
                'category' => 'Travel',
                'is_active' => true,
                'sort_order' => 8
            ],
            [
                'id' => 9,
                'title' => 'Vany Cafe',
                'image' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=600&h=800&fit=crop',
                'description' => 'Cafe dengan suasana hangat dan menu kopi specialty serta makanan ringan yang lezat.',
                'target' => '/',
                'category' => 'Culinary',
                'is_active' => true,
                'sort_order' => 9
            ],
            [
                'id' => 10,
                'title' => 'Vany Fitness',
                'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&h=800&fit=crop',
                'description' => 'Pusat kesehatan dan kebugaran dengan program holistik untuk hidup yang sehat dan seimbang.',
                'target' => '/',
                'category' => 'Health & Fitness',
                'is_active' => true,
                'sort_order' => 10
            ],
            [
                'id' => 11,
                'title' => 'Vany Home Decor',
                'image' => 'https://images.unsplash.com/photo-1564584217132-2271feaeb3c5?w=600&h=800&fit=crop',
                'description' => 'Dekorasi rumah dengan sentuhan kontemporer dan Skandinavia untuk hunian yang indah.',
                'target' => '/',
                'category' => 'Home & Living',
                'is_active' => true,
                'sort_order' => 11
            ]
        ];

        foreach ($galleryItems as $item) {
            HomepageGalleryItem::updateOrCreate(
                ['id' => $item['id']],
                $item
            );
        }
    }
}
