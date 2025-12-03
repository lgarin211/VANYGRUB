<?php

// Vercel-specific configurations
if (isset($_ENV['VERCEL']) || isset($_ENV['NOW_REGION'])) {
    // Set storage paths for Vercel
    $_ENV['VIEW_COMPILED_PATH'] = $_ENV['VIEW_COMPILED_PATH'] ?? '/tmp/views';
    $_ENV['APP_SERVICES_CACHE'] = $_ENV['APP_SERVICES_CACHE'] ?? '/tmp/services.php';
    $_ENV['APP_PACKAGES_CACHE'] = $_ENV['APP_PACKAGES_CACHE'] ?? '/tmp/packages.php';
    $_ENV['APP_CONFIG_CACHE'] = $_ENV['APP_CONFIG_CACHE'] ?? '/tmp/config.php';
    $_ENV['APP_ROUTES_CACHE'] = $_ENV['APP_ROUTES_CACHE'] ?? '/tmp/routes.php';
    $_ENV['APP_EVENTS_CACHE'] = $_ENV['APP_EVENTS_CACHE'] ?? '/tmp/events.php';

    // Ensure cache driver is array for serverless
    $_ENV['CACHE_DRIVER'] = 'array';
    $_ENV['SESSION_DRIVER'] = 'cookie';
    $_ENV['LOG_CHANNEL'] = 'stderr';

    // Create temp directories if they don't exist
    @mkdir('/tmp/views', 0755, true);
    @mkdir('/tmp/cache', 0755, true);
    @mkdir('/tmp/sessions', 0755, true);
}

return [];