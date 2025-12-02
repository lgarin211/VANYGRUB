<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteConfig;

class SiteConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            // META configurations
            [
                'group' => 'meta',
                'key' => 'title',
                'value' => 'VANY GROUB - Premium Lifestyle Collection',
                'type' => 'text',
                'description' => 'Main page title for SEO and browser tab'
            ],
            [
                'group' => 'meta',
                'key' => 'description',
                'value' => 'Discover premium lifestyle products from traditional fashion to modern hospitality services',
                'type' => 'textarea',
                'description' => 'Meta description for search engines'
            ],
            [
                'group' => 'meta',
                'key' => 'keywords',
                'value' => ['vany', 'premium', 'lifestyle', 'fashion', 'hospitality', 'beauty'],
                'type' => 'array',
                'description' => 'SEO keywords for the website'
            ],

            // HERO SECTION
            [
                'group' => 'hero_section',
                'key' => 'title',
                'value' => 'VANY GROUB',
                'type' => 'text',
                'description' => 'Main hero title on homepage'
            ],
            [
                'group' => 'hero_section',
                'key' => 'subtitle',
                'value' => 'Premium Lifestyle Collection',
                'type' => 'text',
                'description' => 'Hero subtitle text'
            ],
            [
                'group' => 'hero_section',
                'key' => 'description',
                'value' => 'Explore our curated collection of premium products and services',
                'type' => 'textarea',
                'description' => 'Hero section description text'
            ],

            // COLORS & BRANDING
            [
                'group' => 'colors',
                'key' => 'primary',
                'value' => '#800000',
                'type' => 'color',
                'description' => 'Primary brand color (Maroon)'
            ],
            [
                'group' => 'colors',
                'key' => 'secondary',
                'value' => '#000000',
                'type' => 'color',
                'description' => 'Secondary brand color (Black)'
            ],
            [
                'group' => 'colors',
                'key' => 'accent',
                'value' => '#ffffff',
                'type' => 'color',
                'description' => 'Accent color (White)'
            ],
            [
                'group' => 'colors',
                'key' => 'gradient',
                'value' => 'linear-gradient(135deg, #800000 0%, #000000 100%)',
                'type' => 'text',
                'description' => 'CSS gradient for backgrounds'
            ],

            // ANIMATION SETTINGS
            [
                'group' => 'animation',
                'key' => 'carousel_interval',
                'value' => 5000,
                'type' => 'number',
                'description' => 'Carousel slide interval in milliseconds'
            ],
            [
                'group' => 'animation',
                'key' => 'transition_duration',
                'value' => 300,
                'type' => 'number',
                'description' => 'Default transition duration in milliseconds'
            ],

            // SITE CONFIG
            [
                'group' => 'site_config',
                'key' => 'site_name',
                'value' => 'VANY GROUB',
                'type' => 'text',
                'description' => 'Website/company name'
            ],
            [
                'group' => 'site_config',
                'key' => 'tagline',
                'value' => 'Premium Lifestyle Collection',
                'type' => 'text',
                'description' => 'Site tagline/slogan'
            ],
            [
                'group' => 'site_config',
                'key' => 'description',
                'value' => 'Your one-stop destination for premium lifestyle products and services',
                'type' => 'textarea',
                'description' => 'General site description'
            ],

            // CONTACT INFORMATION
            [
                'group' => 'contact',
                'key' => 'email',
                'value' => 'info@VANY GROUB.com',
                'type' => 'email',
                'description' => 'Primary contact email'
            ],
            [
                'group' => 'contact',
                'key' => 'phone',
                'value' => '+62 812-3456-7890',
                'type' => 'text',
                'description' => 'Contact phone number'
            ],
            [
                'group' => 'contact',
                'key' => 'address',
                'value' => 'Jl. Premium No. 123, Jakarta, Indonesia',
                'type' => 'textarea',
                'description' => 'Physical address'
            ],

            // SOCIAL MEDIA
            [
                'group' => 'social_media',
                'key' => 'facebook',
                'value' => 'https://facebook.com/VANY GROUB',
                'type' => 'url',
                'description' => 'Facebook page URL'
            ],
            [
                'group' => 'social_media',
                'key' => 'instagram',
                'value' => 'https://instagram.com/VANY GROUB',
                'type' => 'url',
                'description' => 'Instagram profile URL'
            ],
            [
                'group' => 'social_media',
                'key' => 'twitter',
                'value' => 'https://twitter.com/VANY GROUB',
                'type' => 'url',
                'description' => 'Twitter profile URL'
            ],
            [
                'group' => 'social_media',
                'key' => 'youtube',
                'value' => 'https://youtube.com/VANY GROUB',
                'type' => 'url',
                'description' => 'YouTube channel URL'
            ],
        ];

        foreach ($configs as $config) {
            SiteConfig::updateOrCreate(
                ['group' => $config['group'], 'key' => $config['key']],
                $config
            );
        }

        $this->command->info('Site configurations seeded successfully!');
    }
}
