<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;

class SitemapController extends Controller
{
    /**
     * Generate dynamic sitemap.xml
     */
    public function index()
    {
        $urls = [];
        $baseUrl = config('app.url');
        $lastmod = Carbon::now()->format('Y-m-d');

        // Static pages
        $staticPages = [
            ['url' => '/', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => '/vny', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['url' => '/vny/product', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['url' => '/gallery', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['url' => '/about', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['url' => '/transactions', 'priority' => '0.5', 'changefreq' => 'weekly'],
            ['url' => '/vny/cart', 'priority' => '0.4', 'changefreq' => 'monthly'],
            ['url' => '/customerreview', 'priority' => '0.5', 'changefreq' => 'weekly'],
            ['url' => '/customerreview/all', 'priority' => '0.4', 'changefreq' => 'weekly'],
        ];

        foreach ($staticPages as $page) {
            $urls[] = [
                'loc' => $baseUrl . $page['url'],
                'lastmod' => $lastmod,
                'changefreq' => $page['changefreq'],
                'priority' => $page['priority']
            ];
        }

        // Dynamic product pages
        try {
            // Try to get products from API
            $response = Http::timeout(10)->get('https://vanygroup.id/api/vny/products');

            if ($response->successful()) {
                $productsData = $response->json();
                if (isset($productsData['data']) && is_array($productsData['data'])) {
                    foreach ($productsData['data'] as $product) {
                        if (isset($product['id'])) {
                            $urls[] = [
                                'loc' => $baseUrl . '/vny/product/' . $product['id'],
                                'lastmod' => isset($product['updatedAt']) ?
                                    Carbon::parse($product['updatedAt'])->format('Y-m-d') : $lastmod,
                                'changefreq' => 'weekly',
                                'priority' => '0.7'
                            ];
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Fallback to local products if API fails
            $products = Product::where('status', 'active')->get();
            foreach ($products as $product) {
                $urls[] = [
                    'loc' => $baseUrl . '/vny/product/' . $product->id,
                    'lastmod' => $product->updated_at->format('Y-m-d'),
                    'changefreq' => 'weekly',
                    'priority' => '0.7'
                ];
            }
        }

        // Category pages
        try {
            $response = Http::timeout(10)->get('https://vanygroup.id/api/vny/categories');

            if ($response->successful()) {
                $categoriesData = $response->json();
                if (isset($categoriesData['data']) && is_array($categoriesData['data'])) {
                    foreach ($categoriesData['data'] as $category) {
                        if (isset($category['slug'])) {
                            $urls[] = [
                                'loc' => $baseUrl . '/category/' . $category['slug'],
                                'lastmod' => isset($category['updatedAt']) ?
                                    Carbon::parse($category['updatedAt'])->format('Y-m-d') : $lastmod,
                                'changefreq' => 'weekly',
                                'priority' => '0.6'
                            ];
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Fallback to local categories if API fails
            $categories = Category::where('is_active', true)->get();
            foreach ($categories as $category) {
                $urls[] = [
                    'loc' => $baseUrl . '/category/' . $category->slug,
                    'lastmod' => $category->updated_at->format('Y-m-d'),
                    'changefreq' => 'weekly',
                    'priority' => '0.6'
                ];
            }
        }

        // Generate XML
        $xml = $this->generateSitemapXml($urls);

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600', // Cache for 1 hour
        ]);
    }

    /**
     * Generate sitemap XML content
     */
    private function generateSitemapXml($urls)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . "\n";
        $xml .= '        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9' . "\n";
        $xml .= '        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . "\n";

        foreach ($urls as $url) {
            $xml .= '    <url>' . "\n";
            $xml .= '        <loc>' . htmlspecialchars($url['loc']) . '</loc>' . "\n";
            $xml .= '        <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
            $xml .= '        <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
            $xml .= '        <priority>' . $url['priority'] . '</priority>' . "\n";
            $xml .= '    </url>' . "\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Generate robots.txt
     */
    public function robots()
    {
        $content = "User-agent: *\n";
        $content .= "Allow: /\n\n";
        $content .= "# Disallow admin and API documentation\n";
        $content .= "Disallow: /admin\n";
        $content .= "Disallow: /api/documentation\n";
        $content .= "Disallow: /test\n";
        $content .= "Disallow: /tailwind-test\n";
        $content .= "Disallow: /firebase-test\n\n";
        $content .= "# Allow API endpoints for developers\n";
        $content .= "Allow: /api/vny/\n\n";
        $content .= "# Sitemap location\n";
        $content .= "Sitemap: " . config('app.url') . "/sitemap.xml\n";

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Cache-Control' => 'public, max-age=86400', // Cache for 24 hours
        ]);
    }
}
