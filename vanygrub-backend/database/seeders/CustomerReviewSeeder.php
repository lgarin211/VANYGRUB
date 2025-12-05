<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CustomerReview;
use App\Models\Order;
use Illuminate\Support\Str;

class CustomerReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, get existing orders or create dummy orders if none exist
        $existingOrders = Order::pluck('id')->toArray();

        if (empty($existingOrders)) {
            // Create dummy orders if none exist
            for ($i = 1; $i <= 25; $i++) {
                Order::create([
                    'user_id' => 1,
                    'order_number' => 'ORD-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                    'customer_name' => 'Sample Customer ' . $i,
                    'customer_email' => 'customer' . $i . '@example.com',
                    'status' => 'delivered',
                    'subtotal' => rand(200000, 1000000),
                    'discount_amount' => 0,
                    'total_amount' => rand(200000, 1000000),
                    'shipping_address' => 'Sample Address ' . $i . ', Jakarta',
                    'phone' => '08' . rand(1000000000, 9999999999),
                    'notes' => 'Sample order for testing',
                    'created_at' => now()->subDays(rand(1, 60)),
                    'updated_at' => now()->subDays(rand(1, 30))
                ]);
            }
            $existingOrders = Order::pluck('id')->toArray();
        }

        // Sample customer names
        $customerNames = [
            'Andi Pratama',
            'Sari Dewi',
            'Budi Santoso',
            'Maya Sari',
            'Dedi Kurniawan',
            'Fitri Rahmawati',
            'Agus Wibowo',
            'Rina Susanti',
            'Yudi Prasetyo',
            'Indah Permata',
            'Joko Widodo',
            'Sinta Maharani',
            'Rizki Ramadhan',
            'Dewi Lestari',
            'Fajar Nugroho',
            'Lisa Andriani',
            'Tommy Wijaya',
            'Nila Sari',
            'Bayu Setiawan',
            'Ayu Kartika'
        ];

        // Sample email domains
        $emailDomains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com'];

        // Sample review texts
        $reviewTexts = [
            'Sepatu VNY ini benar-benar keren! Kualitas materialnya premium banget dan sangat nyaman dipakai. Desainnya juga modern, cocok buat hangout sama teman-teman. Recommended deh!',
            'Pertama kali beli sepatu di VNY Store dan langsung jatuh cinta! Stylenya unik dan pas banget sama kepribadian saya. Pelayanannya juga ramah sekali.',
            'Wah, sepatu ini exceeded my expectations! Bahan kulitnya halus dan jahitannya rapi. Udah beberapa bulan pakai masih kayak baru. Worth it banget!',
            'Sepatu impian saya akhirnya ketemu! Design-nya elegan tapi tetap sporty. Cocok buat ke kantor atau weekend. Terima kasih VNY Store!',
            'Kualitas sepatu VNY memang tidak diragukan lagi. Sudah 3 pasang sepatu beli disini, semuanya awet dan nyaman. Selalu jadi pilihan utama!',
            'Sepatu yang saya pesan sesuai dengan gambar di website. Packagingnya juga rapi banget. Pas dicoba, ukurannya pas dan empuk. Love it!',
            'Dari segi tampilan sudah pasti keren, tapi yang lebih saya suka itu kenyamanannya. Bisa dipake seharian tanpa pegal-pegal. Great product!',
            'Udah lama naksir sepatu model ini, akhirnya kesampaian beli! Material berkualitas, desain trendy, dan harga reasonable. Perfect combination!',
            'Sepatu VNY ini jadi favorit baru saya! Gampang di-mix and match sama outfit apapun. Banyak yang komplin bagus sepatunya. Definitely will buy again!',
            'Pelayanan customer service VNY Store sangat memuaskan. Sepatu sampai dengan kondisi perfect. Kualitas sesuai harga, malah lebih worth it!',
            'Sepatu ini cocok banget buat yang suka tampil stylish tapi tetap comfortable. Bahan breathable jadi kaki tidak berkeringat berlebihan.',
            'Packaging VNY Store selalu on point! Sepatu datang dalam kondisi sempurna. Design box-nya juga aesthetic, cocok buat kado.',
            'Sudah jadi pelanggan setia VNY Store! Setiap koleksi baru pasti saya follow. Kualitas consistent dan selalu trendy. Keep it up!',
            'Sepatu ini benar-benar game changer buat penampilan saya. Confidence level naik drastis pas pake sepatu VNY. Highly recommended!',
            'Beli sepatu ini buat hadiah ulang tahun pacar, dia sampai speechless! Kualitas premium dengan design yang eye-catching. Success!',
            'Comfort level sepatu VNY ini amazing! Cocok banget buat yang mobilitas tinggi. Sudah jalan jauh tetap nyaman di kaki.',
            'Delivery cepat, packaging aman, kualitas excellent. Apa lagi yang bisa diminta? VNY Store memang the best online shoe store!',
            'Sepatu VNY ini investment yang worth it banget. Designnya timeless jadi tidak mudah bosan. Material berkualitas pasti awet bertahun-tahun.',
            'First impression saat buka package: WOW! Sepatu real-nya lebih bagus dari foto. Detailnya sangat diperhatikan. Impressed!',
            'Terima kasih VNY Store sudah provide sepatu berkualitas dengan harga terjangkau. Pelayanan excellent, produk excellent. Perfect!'
        ];

        // Create 20 sample customer reviews
        for ($i = 0; $i < 20; $i++) {
            // Generate unique review token
            $reviewToken = Str::random(32);

            // Random customer name and email
            $customerName = $customerNames[$i];
            $emailName = strtolower(str_replace(' ', '.', $customerName));
            $emailDomain = $emailDomains[array_rand($emailDomains)];
            $customerEmail = $emailName . '@' . $emailDomain;

            // Use existing order IDs
            $orderId = $existingOrders[array_rand($existingOrders)];

            CustomerReview::create([
                'order_id' => $orderId,
                'customer_name' => $customerName,
                'customer_email' => $customerEmail,
                'photo_url' => 'https://vanygroup.id/storage/temp/01KBCDV2F4NZ67ZYQGV5V9R8RW.png',
                'review_text' => $reviewTexts[$i],
                'rating' => rand(4, 5), // Random rating between 4-5 stars
                'review_token' => $reviewToken,
                'is_approved' => rand(0, 1) === 1, // Randomly approved or not
                'is_featured' => rand(0, 1) === 1 && $i < 8, // Only first 8 can be featured
                'created_at' => now()->subDays(rand(1, 30)), // Random date within last 30 days
                'updated_at' => now()->subDays(rand(1, 30))
            ]);
        }

        // Create some additional featured reviews to ensure we have enough featured content
        $featuredReviews = [
            // [
            //     'customer_name' => 'Celebrity Customer',
            //     'email' => 'celebrity@vnystore.com',
            //     'review_text' => 'VNY Store adalah pilihan terbaik untuk sepatu berkualitas premium! Sebagai public figure, saya sangat memperhatikan penampilan dan sepatu VNY selalu jadi andalan. Quality dan style-nya benar-benar outstanding!',
            //     'rating' => 5,
            //     'is_featured' => true
            // ],
            // [
            //     'customer_name' => 'Fashion Influencer',
            //     'email' => 'influencer@vnystore.com',
            //     'review_text' => 'Koleksi sepatu VNY selalu update mengikuti trend fashion terkini. Sebagai fashion enthusiast, saya sangat appreciate dengan design dan kualitas yang konsisten. Always my go-to brand!',
            //     'rating' => 5,
            //     'is_featured' => true
            // ]
        ];

        foreach ($featuredReviews as $index => $review) {
            // Use existing order IDs for featured reviews too
            $orderId = $existingOrders[array_rand($existingOrders)];

            CustomerReview::create([
                'order_id' => $orderId,
                'customer_name' => $review['customer_name'],
                'customer_email' => $review['email'],
                'photo_url' => 'https://vanygroup.id/storage/temp/01KBCDV2F4NZ67ZYQGV5V9R8RW.png',
                'review_text' => $review['review_text'],
                'rating' => $review['rating'],
                'review_token' => Str::random(32),
                'is_approved' => true,
                'is_featured' => $review['is_featured'],
                'created_at' => now()->subDays(rand(1, 15)),
                'updated_at' => now()->subDays(rand(1, 15))
            ]);
        }
    }
}
