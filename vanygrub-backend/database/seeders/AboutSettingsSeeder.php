<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AboutSetting;

class AboutSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create VNY about setting with Swiss Fineline inspired design
        AboutSetting::create([
            'brand' => 'vny',
            'title' => 'VNY Group',
            'subtitle' => 'Excellence in Every Detail',
            'description' => 'Delivering premium quality products with meticulous attention to detail and Swiss-inspired craftsmanship standards.',
            'colors' => [
                'primary' => '#f59e0b',
                'secondary' => '#dc2626',
                'accent' => '#ea580c'
            ],
            'hero_data' => [
                'title' => 'VNY Group',
                'subtitle' => 'Excellence in Every Detail',
                'background' => 'amber-900',
                'pattern' => 'traditional',
                'primary_color' => '#f59e0b',
                'secondary_color' => '#dc2626'
            ],
            'history_data' => [
                'main_title' => 'Our Story',
                'timeline' => [
                    [
                        'poster' => null,
                        'tahun' => '2019',
                        'title' => 'Company Founded',
                        'deskripsi' => 'VNY Group was established with a vision of Swiss precision and quality standards.',
                        'color' => '#1e40af',
                        'bgcolor' => '#f8fafc'
                    ],
                    [
                        'poster' => null,
                        'tahun' => '2021',
                        'title' => 'First Major Milestone',
                        'deskripsi' => 'Reached 100+ satisfied customers and expanded our product line.',
                        'color' => '#dc2626',
                        'bgcolor' => '#fef2f2'
                    ],
                    [
                        'poster' => null,
                        'tahun' => '2023',
                        'title' => 'Quality Recognition',
                        'deskripsi' => 'Achieved premium quality standards and customer satisfaction excellence.',
                        'color' => '#f59e0b',
                        'bgcolor' => '#fffbeb'
                    ]
                ]
            ],
            'philosophy_data' => [
                [
                    'color' => '#f59e0b',
                    'name' => 'Precision',
                    'meaning' => 'Swiss-inspired attention to detail in every product we create'
                ],
                [
                    'color' => '#dc2626',
                    'name' => 'Quality',
                    'meaning' => 'Premium materials and craftsmanship for lasting excellence'
                ],
                [
                    'color' => '#ea580c',
                    'name' => 'Innovation',
                    'meaning' => 'Continuous improvement and modern design thinking'
                ],
                [
                    'color' => '#059669',
                    'name' => 'Heritage',
                    'meaning' => 'Respecting tradition while embracing the future'
                ]
            ],
            'contact_data' => [
                'email' => 'info@vnygroup.com',
                'phone' => '+62 813-1587-1101',
                'location' => 'Indonesia'
            ],
            'is_active' => true
        ]);

        // Create VanySongket about setting
        AboutSetting::create([
            'brand' => 'vanysongket',
            'title' => 'VanySongket',
            'subtitle' => 'Traditional Elegance Redefined',
            'description' => 'Premium songket craftsmanship that bridges traditional artistry with contemporary sophistication.',
            'colors' => [
                'primary' => '#dc2626',
                'secondary' => '#b91c1c',
                'accent' => '#991b1b'
            ],
            'hero_data' => [
                'title' => 'VanySongket',
                'subtitle' => 'Traditional Elegance Redefined',
                'background' => 'red-900',
                'pattern' => 'traditional',
                'primary_color' => '#dc2626',
                'secondary_color' => '#b91c1c'
            ],
            'history_data' => [
                'main_title' => 'Cultural Heritage',
                'timeline' => [
                    [
                        'poster' => null,
                        'tahun' => '2020',
                        'title' => 'Preserving Tradition',
                        'deskripsi' => 'VanySongket was founded to preserve ancient songket weaving techniques.',
                        'color' => '#dc2626',
                        'bgcolor' => '#fef2f2'
                    ],
                    [
                        'poster' => null,
                        'tahun' => '2022',
                        'title' => 'Authentic Craftsmanship',
                        'deskripsi' => 'Developed premium authentic songket products with modern aesthetic appeal.',
                        'color' => '#b91c1c',
                        'bgcolor' => '#fef2f2'
                    ]
                ]
            ],
            'philosophy_data' => [
                [
                    'color' => '#dc2626',
                    'name' => 'Heritage',
                    'meaning' => 'Preserving traditional songket weaving techniques passed down through generations'
                ],
                [
                    'color' => '#f59e0b',
                    'name' => 'Authenticity',
                    'meaning' => 'Using genuine gold and silver threads with premium fabric materials'
                ],
                [
                    'color' => '#7c3aed',
                    'name' => 'Elegance',
                    'meaning' => 'Creating sophisticated designs that honor tradition while embracing modernity'
                ]
            ],
            'contact_data' => [
                'email' => 'info@vanysongket.com',
                'phone' => '+62 813 4567 8901',
                'location' => 'Palembang, Indonesia'
            ],
            'is_active' => true
        ]);

        // Create VanyVilla about setting
        AboutSetting::create([
            'brand' => 'vanyvilla',
            'title' => 'VanyVilla',
            'subtitle' => 'Hospitality Perfected',
            'description' => 'Premium accommodation services that exemplify Swiss hospitality standards with meticulous attention to guest satisfaction.',
            'colors' => [
                'primary' => '#065f46',
                'secondary' => '#047857',
                'accent' => '#059669'
            ],
            'hero_data' => [
                'title' => 'VanyVilla',
                'subtitle' => 'Hospitality Perfected',
                'background' => 'green-900',
                'pattern' => 'traditional',
                'primary_color' => '#065f46',
                'secondary_color' => '#047857'
            ],
            'history_data' => [
                'main_title' => 'Premium Experience',
                'timeline' => [
                    [
                        'poster' => null,
                        'tahun' => '2021',
                        'title' => 'Hospitality Redefined',
                        'deskripsi' => 'VanyVilla launched with Swiss hospitality precision and Indonesian warmth.',
                        'color' => '#065f46',
                        'bgcolor' => '#f0fdf4'
                    ],
                    [
                        'poster' => null,
                        'tahun' => '2023',
                        'title' => 'Excellence Recognition',
                        'deskripsi' => 'Achieved premium service standards and guest satisfaction excellence.',
                        'color' => '#047857',
                        'bgcolor' => '#f0fdf4'
                    ]
                ]
            ],
            'philosophy_data' => [
                [
                    'color' => '#065f46',
                    'name' => 'Service',
                    'meaning' => 'Exceptional hospitality with Swiss precision and Indonesian warmth'
                ],
                [
                    'color' => '#1d4ed8',
                    'name' => 'Comfort',
                    'meaning' => 'Premium facilities and amenities for the ultimate guest experience'
                ],
                [
                    'color' => '#f59e0b',
                    'name' => 'Location',
                    'meaning' => 'Strategic positioning with easy access to key destinations'
                ]
            ],
            'contact_data' => [
                'email' => 'info@vanyvilla.com',
                'phone' => '+62 814 5678 9012',
                'location' => 'Toba, Indonesia'
            ],
            'is_active' => true
        ]);

        $this->command->info('About settings seeded successfully!');
    }
}
