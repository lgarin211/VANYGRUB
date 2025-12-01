<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    /**
     * Get homepage constants data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConstants()
    {
        $constants = [
            'META' => [
                'TITLE' => 'VANYGRUB - Premium Lifestyle Collection',
                'DESCRIPTION' => 'Discover premium lifestyle products from traditional fashion to modern hospitality services',
                'KEYWORDS' => ['vany', 'premium', 'lifestyle', 'fashion', 'hospitality', 'beauty']
            ],

            'HERO_SECTION' => [
                'TITLE' => 'Welcome to VANYGRUB',
                'SUBTITLE' => 'Premium Lifestyle Collection',
                'DESCRIPTION' => 'Explore our curated collection of premium products and services'
            ],

            'GALLERY_ITEMS' => [
                [
                    'id' => 1,
                    'title' => 'Vany Songket',
                    'image' => 'https://images.unsplash.com/photo-1546548970-71785318a17b?w=600&h=800&fit=crop',
                    'description' => 'Koleksi songket tradisional dengan desain modern yang memadukan kearifan lokal dengan gaya kontemporer. Dibuat dengan benang emas dan perak berkualitas tinggi.',
                    'target' => '/',
                    'category' => 'Traditional Fashion'
                ],
                [
                    'id' => 2,
                    'title' => 'Vny Toba Shoes',
                    'image' => 'https://vanyadmin.progesio.my.id/storage/temp/01KBA3TQSB8X78WRK1YP7E9JT0.png',
                    'description' => 'Sepatu berkualitas tinggi dengan design yang nyaman dan gaya yang elegan untuk aktivitas sehari-hari. Terbuat dari kulit asli premium.',
                    'target' => '/vny',
                    'category' => 'Footwear'
                ],
                [
                    'id' => 3,
                    'title' => 'Vany Villa',
                    'image' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=600&h=800&fit=crop',
                    'description' => 'Villa mewah dengan pemandangan indah dan fasilitas lengkap untuk liburan yang tak terlupakan. Dilengkapi dengan kolam renang pribadi.',
                    'target' => '/',
                    'category' => 'Hospitality'
                ],
                [
                    'id' => 4,
                    'title' => 'Vany Apartement',
                    'image' => 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=600&h=800&fit=crop',
                    'description' => 'Apartemen modern dengan lokasi strategis dan fasilitas premium untuk hunian yang nyaman. Dilengkapi dengan gym dan rooftop garden.',
                    'target' => '/',
                    'category' => 'Real Estate'
                ],
                [
                    'id' => 5,
                    'title' => 'Vany Shalon',
                    'image' => 'https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=600&h=800&fit=crop',
                    'description' => 'Salon kecantikan dengan layanan profesional dan perawatan terbaik untuk penampilan yang memukau. Treatment dengan produk premium.',
                    'target' => '/',
                    'category' => 'Beauty & Wellness'
                ],
                [
                    'id' => 6,
                    'title' => 'Vany Restaurant',
                    'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=600&h=800&fit=crop',
                    'description' => 'Restoran dengan cita rasa autentik dan suasana yang nyaman untuk pengalaman kuliner yang tak terlupakan.',
                    'target' => '/',
                    'category' => 'Culinary'
                ],
                [
                    'id' => 7,
                    'title' => 'Vany Hotel',
                    'image' => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=600&h=800&fit=crop',
                    'description' => 'Hotel bintang lima dengan layanan premium dan fasilitas mewah untuk pengalaman menginap yang istimewa.',
                    'target' => '/',
                    'category' => 'Hospitality'
                ],
                [
                    'id' => 8,
                    'title' => 'Vany Travel',
                    'image' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=600&h=800&fit=crop',
                    'description' => 'Layanan travel dan wisata dengan paket liburan terbaik ke destinasi menawan di seluruh dunia.',
                    'target' => '/',
                    'category' => 'Travel'
                ],
                [
                    'id' => 9,
                    'title' => 'Vany Cafe',
                    'image' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=600&h=800&fit=crop',
                    'description' => 'Cafe dengan suasana hangat dan menu kopi specialty serta makanan ringan yang lezat.',
                    'target' => '/',
                    'category' => 'Culinary'
                ],
                [
                    'id' => 10,
                    'title' => 'Vany Fitness',
                    'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&h=800&fit=crop',
                    'description' => 'Pusat kesehatan dan kebugaran dengan program holistik untuk hidup yang sehat dan seimbang.',
                    'target' => '/',
                    'category' => 'Health & Fitness'
                ],
                [
                    'id' => 11,
                    'title' => 'Vany Home Decor',
                    'image' => 'https://images.unsplash.com/photo-1564584217132-2271feaeb3c5?w=600&h=800&fit=crop',
                    'description' => 'Dekorasi rumah dengan sentuhan kontemporer dan Skandinavia untuk hunian yang indah.',
                    'target' => '/',
                    'category' => 'Home & Living'
                ]
            ],

            'CATEGORIES' => [
                'Traditional Fashion',
                'Footwear',
                'Hospitality',
                'Real Estate',
                'Beauty & Wellness',
                'Culinary',
                'Travel',
                'Health & Fitness',
                'Home & Living'
            ],

            'COLORS' => [
                'PRIMARY' => '#800000', // Maroon
                'SECONDARY' => '#000000', // Black
                'ACCENT' => '#ffffff', // White
                'GRADIENT' => 'linear-gradient(135deg, #800000 0%, #000000 100%)'
            ],

            'ANIMATION' => [
                'CAROUSEL_INTERVAL' => 5000, // 5 seconds
                'TRANSITION_DURATION' => 300
            ]
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Homepage constants retrieved successfully',
            'data' => $constants
        ]);
    }

    /**
     * Get specific gallery item by ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGalleryItem($id)
    {
        $constants = $this->getConstants()->getData();
        $galleryItems = $constants->data->GALLERY_ITEMS;

        $item = collect($galleryItems)->firstWhere('id', (int) $id);

        if (!$item) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gallery item not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Gallery item retrieved successfully',
            'data' => $item
        ]);
    }

    /**
     * Get gallery items by category
     *
     * @param string $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGalleryByCategory($category)
    {
        $constants = $this->getConstants()->getData();
        $galleryItems = $constants->data->GALLERY_ITEMS;

        $filteredItems = collect($galleryItems)->filter(function ($item) use ($category) {
            return strtolower($item->category) === strtolower($category);
        })->values();

        return response()->json([
            'status' => 'success',
            'message' => 'Gallery items by category retrieved successfully',
            'data' => $filteredItems,
            'count' => $filteredItems->count()
        ]);
    }

    /**
     * Get site configuration
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSiteConfig()
    {
        $siteConfig = [
            'SITE_NAME' => 'VANYGRUB',
            'TAGLINE' => 'Premium Lifestyle Collection',
            'DESCRIPTION' => 'Your one-stop destination for premium lifestyle products and services',
            'CONTACT' => [
                'EMAIL' => 'info@vanygrub.com',
                'PHONE' => '+62 812-3456-7890',
                'ADDRESS' => 'Jl. Premium No. 123, Jakarta, Indonesia'
            ],
            'SOCIAL_MEDIA' => [
                'FACEBOOK' => 'https://facebook.com/vanygrub',
                'INSTAGRAM' => 'https://instagram.com/vanygrub',
                'TWITTER' => 'https://twitter.com/vanygrub',
                'YOUTUBE' => 'https://youtube.com/vanygrub'
            ],
            'NAVIGATION' => [
                'MAIN_MENU' => [
                    ['label' => 'Home', 'url' => '/', 'active' => true],
                    ['label' => 'VNY Products', 'url' => '/vny', 'active' => false],
                    ['label' => 'Gallery', 'url' => '/gallery', 'active' => false],
                    ['label' => 'About', 'url' => '/about', 'active' => false],
                    ['label' => 'Transactions', 'url' => '/transactions', 'active' => false]
                ],
                'FOOTER_LINKS' => [
                    'SERVICES' => [
                        ['label' => 'Traditional Fashion', 'url' => '/services/fashion'],
                        ['label' => 'Footwear', 'url' => '/services/footwear'],
                        ['label' => 'Hospitality', 'url' => '/services/hospitality'],
                        ['label' => 'Real Estate', 'url' => '/services/realestate']
                    ],
                    'COMPANY' => [
                        ['label' => 'About Us', 'url' => '/about'],
                        ['label' => 'Careers', 'url' => '/careers'],
                        ['label' => 'Press', 'url' => '/press'],
                        ['label' => 'Contact', 'url' => '/contact']
                    ]
                ]
            ]
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Site configuration retrieved successfully',
            'data' => $siteConfig
        ]);
    }
}