<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeroSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataHomePath = base_path('../constants/dataHome.json');

        if (file_exists($dataHomePath)) {
            $homeData = json_decode(file_get_contents($dataHomePath), true);

            if (isset($homeData['heroSection']['slides'])) {
                foreach ($homeData['heroSection']['slides'] as $slide) {
                    \App\Models\HeroSection::create([
                        'title' => $slide['title'],
                        'subtitle' => $slide['subtitle'],
                        'description' => $slide['description'],
                        'image' => $slide['image'],
                        'bg_color' => $slide['bgColor'],
                        'text_color' => $slide['textColor'],
                        'button_text' => $slide['buttonText'],
                        'price' => $slide['price'],
                        'is_active' => true,
                        'sort_order' => $slide['id']
                    ]);
                }
            }
        }
    }
}
