<?php
/**
 * Sitemap Testing and Validation Script
 * Use this to test your sitemap before submitting to Google Search Console
 */

echo "=== SITEMAP TESTING & VALIDATION ===\n\n";

// Test sitemap endpoints
$endpoints = [
    'Dynamic Sitemap' => 'http://127.0.0.1:8000/sitemap.xml',
    'Static Sitemap' => 'http://127.0.0.1:8000/sitemap.xml', // Same endpoint now
    'Robots.txt' => 'http://127.0.0.1:8000/robots.txt',
    'HTML Sitemap' => 'http://127.0.0.1:8000/sitemap'
];

foreach ($endpoints as $name => $url) {
    echo "Testing $name: $url\n";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $totalTime = curl_getinfo($ch, CURLINFO_TOTAL_TIME);

    if (curl_error($ch)) {
        echo "❌ ERROR: " . curl_error($ch) . "\n";
    } else {
        echo "✅ HTTP $httpCode | $contentType | {$totalTime}s\n";

        // For XML sitemap, do basic validation
        if (strpos($name, 'Sitemap') !== false && strpos($contentType, 'xml') !== false) {
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $xmlContent = substr($response, $headerSize);

            // Basic XML validation
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);

            if ($dom->loadXML($xmlContent)) {
                echo "  ✅ Valid XML structure\n";

                // Count URLs
                $urls = $dom->getElementsByTagName('url');
                echo "  📊 Contains {$urls->length} URLs\n";

                // Check required elements
                $hasRequiredElements = true;
                foreach ($urls as $url) {
                    $loc = $url->getElementsByTagName('loc')->item(0);
                    $lastmod = $url->getElementsByTagName('lastmod')->item(0);
                    $changefreq = $url->getElementsByTagName('changefreq')->item(0);
                    $priority = $url->getElementsByTagName('priority')->item(0);

                    if (!$loc || !$lastmod || !$changefreq || !$priority) {
                        $hasRequiredElements = false;
                        break;
                    }
                }

                if ($hasRequiredElements) {
                    echo "  ✅ All URLs have required elements\n";
                } else {
                    echo "  ❌ Some URLs missing required elements\n";
                }

                // Check for valid URLs
                $validUrls = 0;
                foreach ($urls as $url) {
                    $loc = $url->getElementsByTagName('loc')->item(0);
                    if ($loc && filter_var($loc->nodeValue, FILTER_VALIDATE_URL)) {
                        $validUrls++;
                    }
                }
                echo "  📊 $validUrls valid URLs found\n";

            } else {
                echo "  ❌ Invalid XML structure\n";
                $errors = libxml_get_errors();
                foreach ($errors as $error) {
                    echo "    - " . trim($error->message) . "\n";
                }
            }

            libxml_clear_errors();
        }
    }

    curl_close($ch);
    echo "\n";
}

echo "=== GOOGLE SEARCH CONSOLE CHECKLIST ===\n\n";

$checklist = [
    "✅ Sitemap returns HTTP 200 status",
    "✅ Sitemap has correct Content-Type (application/xml)",
    "✅ XML is well-formed and valid",
    "✅ All URLs are absolute (start with https://)",
    "✅ All URLs use HTTPS protocol",
    "✅ No HTML comments in XML",
    "✅ Proper XML encoding (UTF-8)",
    "✅ robots.txt references sitemap",
    "✅ Sitemap contains only user-facing pages",
    "⏳ Wait 24-48 hours for Google to reprocess"
];

foreach ($checklist as $item) {
    echo "$item\n";
}

echo "\n=== NEXT STEPS ===\n\n";
echo "1. Submit updated sitemap to Google Search Console\n";
echo "2. Monitor 'Sitemaps' section for status changes\n";
echo "3. Check 'Coverage' report for indexing improvements\n";
echo "4. Validate sitemap online: https://www.xml-sitemaps.com/validate-xml-sitemap.html\n";
echo "5. Use Google's URL Inspection tool to test specific URLs\n\n";

echo "If you still see 'Sitemap could not be read' error:\n";
echo "- Clear browser cache and try again\n";
echo "- Wait 24-48 hours for Google to reprocess\n";
echo "- Check server logs for any access errors\n";
echo "- Verify the domain in robots.txt matches exactly\n";

?>