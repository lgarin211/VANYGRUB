<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VANY GROUP -  Exclusive Batak Ethnic Collection')</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('description', 'VANY GROUP - Discover premium sneakers, fashion, and footwear collection from Vany Villa Balige, Vany Villa Bandung, and VNY Fashion. Quality products by Melfarina Sianipar.')">
    <meta name="keywords" content="vany songket,vany group, premium sneakers, fashion, footwear, vany villa balige, vany villa bandung, vny fashion, melfarina sianipar, sepatu premium, koleksi fashion, branded shoes">
    <meta name="author" content="VANY GROUP - Melfarina Sianipar">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Indonesian">
    <meta name="geo.region" content="ID">
    <meta name="geo.country" content="Indonesia">
    <meta name="geo.placename" content="Balige, Bandung, Indonesia">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('og_title', 'VANY GROUP -  Exclusive Batak Ethnic Collection')">
    <meta property="og:description" content="@yield('og_description', 'Discover premium sneakers, fashion, and footwear collection from Vany Villa Balige, Vany Villa Bandung, and VNY Fashion.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQdOGheV5b6MiiOF3tc7Sam_QMPFqPEwTEzZA&s')">
    <meta property="og:site_name" content="VANY GROUP">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'VANY GROUP -  Exclusive Batak Ethnic Collection')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Discover premium sneakers, fashion, and footwear collection from Vany Villa Balige, Vany Villa Bandung, and VNY Fashion.')">
    <meta name="twitter:image" content="@yield('twitter_image', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQdOGheV5b6MiiOF3tc7Sam_QMPFqPEwTEzZA&s')">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Sitemap and Robots -->
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ route('sitemap') }}">

    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "VANY GROUP",
        "alternateName": ["VNY Fashion", "Vany Villa Balige", "Vany Villa Bandung"],
        "url": "{{ config('app.url') }}",
        "logo": "{{ config('app.url') }}/images/vny-logo.png",
        "description": "Premium sneakers, fashion, and footwear collection specializing in Batak ethnic designs and modern fashion.",
        "founder": {
            "@type": "Person",
            "name": "Melfarina Sianipar"
        },
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "ID",
            "addressRegion": "North Sumatra",
            "addressLocality": "Balige"
        },
        "sameAs": [
            "https://facebook.com/vanygroup",
            "https://instagram.com/vanygroup"
        ],
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+62-813-1587-1101",
            "contactType": "Customer Service",
            "availableLanguage": ["Indonesian", "English"]
        }
    }
    </script>

    @yield('structured_data')
    <meta name="twitter:image" content="@yield('twitter_image', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQdOGheV5b6MiiOF3tc7Sam_QMPFqPEwTEzZA&s')">

    <!-- Brand & Social Media References -->
    <meta name="brand:vany-villa-balige" content="@vanyvillabalige">
    <meta name="brand:vany-villa-bandung" content="@vanyvillabandung">
    <meta name="brand:vany-collection" content="@vanycollection_">
    <meta name="brand:vny-fashion" content="@vny.fashion">
    <meta name="brand:founder" content="@melfarina_sianipar">

    <!-- Additional SEO -->
    <meta name="theme-color" content="#800020">
    <meta name="msapplication-TileColor" content="#800020">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="format-detection" content="telephone=no">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQdOGheV5b6MiiOF3tc7Sam_QMPFqPEwTEzZA&s">
    <link rel="shortcut icon" type="image/x-icon" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQdOGheV5b6MiiOF3tc7Sam_QMPFqPEwTEzZA&s">
    <link rel="apple-touch-icon" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQdOGheV5b6MiiOF3tc7Sam_QMPFqPEwTEzZA&s">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Vite Assets (includes Tailwind CSS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <!-- AOS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">




    <!-- Global Styles -->
    <style>
        :root {
          --foreground-rgb: 0, 0, 0;
          --background-start-rgb: 214, 219, 220;
          --background-end-rgb: 255, 255, 255;
        }

        @media (prefers-color-scheme: dark) {
          :root {
            --foreground-rgb: 255, 255, 255;
            --background-start-rgb: 0, 0, 0;
            --background-end-rgb: 0, 0, 0;
          }
        }

        body {
            font-family: 'Inter', sans-serif;
            color: rgb(var(--foreground-rgb));
            background: linear-gradient(
                to bottom,
                transparent,
                rgb(var(--background-end-rgb))
              )
              rgb(var(--background-start-rgb));
        }

        @yield('styles')
    </style>

    @yield('head')
</head>
<body>
    @yield('content')

    <!-- Initialize AOS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    easing: 'ease-in-out',
                    once: true
                });
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
