<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageSetting;

class HomepageSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'section_name' => 'welcome',
                'is_active' => true,
                'display_order' => 1,
                'welcome_badge' => 'Selamat Datang',
                'welcome_title' => 'VANY GROUP',
                'welcome_tagline' => 'Keunggulan Tradisi & Inovasi Modern',
                'welcome_description' => 'VANY GROUP adalah rumah bagi brand-brand premium yang menggabungkan kekayaan warisan budaya Indonesia dengan desain kontemporer. Dari fashion hingga hospitality, setiap brand kami mencerminkan komitmen terhadap kualitas, kerajinan tangan, dan pengalaman pelanggan yang luar biasa.',
                'highlight_1_number' => '3+',
                'highlight_1_text' => 'Brand Premium',
                'highlight_2_number' => '100%',
                'highlight_2_text' => 'Kualitas Terjamin',
                'welcome_button_text' => 'Jelajahi Brand',
                'welcome_button_link' => '#brands-section',
            ],
            [
                'section_name' => 'brands',
                'is_active' => true,
                'display_order' => 2,
                'brand_section_title' => 'Brand Kami',
                'brand_featured_title' => 'Koleksi Premium VANY GROUP',
                'brand_featured_description' => 'Temukan produk berkualitas premium dengan perhatian detail yang teliti dan kerajinan tradisional yang dipadukan dengan sentuhan modern untuk gaya hidup Anda.',
                'brand_button_text' => 'Jelajahi Brand VNY',
                'brand_button_link' => '/vny',
            ],
            [
                'section_name' => 'values',
                'is_active' => true,
                'display_order' => 3,
                'value_1_number' => '01',
                'value_1_title' => 'Kualitas Kerajinan Tangan',
                'value_1_description' => 'Setiap produk dalam koleksi VANY GROUP merepresentasikan komitmen kami terhadap kualitas luar biasa dengan perhatian detail yang cermat. Kami menggabungkan teknik tradisional warisan budaya Indonesia dengan inovasi modern untuk menciptakan produk yang timeless dan berkelas.',
                'value_1_button_text' => 'Pelajari Lebih Lanjut',
                'value_1_button_link' => '#portfolio-section',
                'value_2_number' => '02',
                'value_2_title' => 'Warisan Budaya & Inovasi',
                'value_2_description' => 'Menjembatani warisan budaya Indonesia yang kaya dengan desain kontemporer, brand-brand kami merayakan kekayaan budaya lokal sambil memenuhi kebutuhan gaya hidup modern. VNY untuk fashion kontemporer, VanySongket untuk kain tradisional berkualitas, dan VanyVilla untuk pengalaman hospitality yang berkesan.',
                'value_2_button_text' => 'Jelajahi Warisan',
                'value_2_button_link' => '/vny/about',
            ],
            [
                'section_name' => 'portfolio',
                'is_active' => true,
                'display_order' => 4,
                'portfolio_title' => 'Portofolio Brand Kami',
                'portfolio_subtitle' => 'Jelajahi beragam brand di bawah naungan VANY GROUP, masing-masing merepresentasikan keunggulan di bidangnya',
            ],
        ];

        foreach ($settings as $setting) {
            HomepageSetting::updateOrCreate(
                ['section_name' => $setting['section_name']],
                $setting
            );
        }
    }
}
