<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Homepage Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains all the configuration for the homepage constants
    | that will be delivered via API to the frontend application.
    |
    */

    'meta' => [
        'title' => env('HOMEPAGE_TITLE', 'VANYGRUB - Premium Lifestyle Collection'),
        'description' => env('HOMEPAGE_DESCRIPTION', 'Discover premium lifestyle products from traditional fashion to modern hospitality services'),
        'keywords' => ['vany', 'premium', 'lifestyle', 'fashion', 'hospitality', 'beauty']
    ],

    'hero_section' => [
        'title' => env('HERO_TITLE', 'Welcome to VANYGRUB'),
        'subtitle' => env('HERO_SUBTITLE', 'Premium Lifestyle Collection'),
        'description' => env('HERO_DESCRIPTION', 'Explore our curated collection of premium products and services')
    ],

    'categories' => [
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

    'colors' => [
        'primary' => env('BRAND_PRIMARY_COLOR', '#800000'), // Maroon
        'secondary' => env('BRAND_SECONDARY_COLOR', '#000000'), // Black
        'accent' => env('BRAND_ACCENT_COLOR', '#ffffff'), // White
        'gradient' => env('BRAND_GRADIENT', 'linear-gradient(135deg, #800000 0%, #000000 100%)')
    ],

    'animation' => [
        'carousel_interval' => env('CAROUSEL_INTERVAL', 5000), // 5 seconds
        'transition_duration' => env('TRANSITION_DURATION', 300)
    ],

    'site_config' => [
        'site_name' => env('SITE_NAME', 'VANYGRUB'),
        'tagline' => env('SITE_TAGLINE', 'Premium Lifestyle Collection'),
        'description' => env('SITE_DESCRIPTION', 'Your one-stop destination for premium lifestyle products and services'),

        'contact' => [
            'email' => env('CONTACT_EMAIL', 'info@vanygrub.com'),
            'phone' => env('CONTACT_PHONE', '+62 812-3456-7890'),
            'address' => env('CONTACT_ADDRESS', 'Jl. Premium No. 123, Jakarta, Indonesia')
        ],

        'social_media' => [
            'facebook' => env('SOCIAL_FACEBOOK', 'https://facebook.com/vanygrub'),
            'instagram' => env('SOCIAL_INSTAGRAM', 'https://instagram.com/vanygrub'),
            'twitter' => env('SOCIAL_TWITTER', 'https://twitter.com/vanygrub'),
            'youtube' => env('SOCIAL_YOUTUBE', 'https://youtube.com/vanygrub')
        ],

        'navigation' => [
            'main_menu' => [
                ['label' => 'Home', 'url' => '/', 'active' => true],
                ['label' => 'VNY Products', 'url' => '/vny', 'active' => false],
                ['label' => 'Gallery', 'url' => '/gallery', 'active' => false],
                ['label' => 'About', 'url' => '/about', 'active' => false],
                ['label' => 'Transactions', 'url' => '/transactions', 'active' => false]
            ],

            'footer_links' => [
                'services' => [
                    ['label' => 'Traditional Fashion', 'url' => '/services/fashion'],
                    ['label' => 'Footwear', 'url' => '/services/footwear'],
                    ['label' => 'Hospitality', 'url' => '/services/hospitality'],
                    ['label' => 'Real Estate', 'url' => '/services/realestate']
                ],
                'company' => [
                    ['label' => 'About Us', 'url' => '/about'],
                    ['label' => 'Careers', 'url' => '/careers'],
                    ['label' => 'Press', 'url' => '/press'],
                    ['label' => 'Contact', 'url' => '/contact']
                ]
            ]
        ]
    ]
];