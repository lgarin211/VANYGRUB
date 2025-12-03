<?php

// Vercel Deployment Checker
// Run this script to check if your Laravel app is ready for Vercel deployment

echo "🚀 VANYGRUB Vercel Deployment Checker\n";
echo "=====================================\n\n";

$checks = [];

// Check if vercel.json exists
$checks[] = [
    'name' => 'vercel.json configuration',
    'status' => file_exists(__DIR__ . '/vercel.json'),
    'message' => file_exists(__DIR__ . '/vercel.json') ? 'Found' : 'Missing - create vercel.json'
];

// Check if api/index.php exists
$checks[] = [
    'name' => 'api/index.php entry point',
    'status' => file_exists(__DIR__ . '/api/index.php'),
    'message' => file_exists(__DIR__ . '/api/index.php') ? 'Found' : 'Missing - create api/index.php'
];

// Check .env.production
$checks[] = [
    'name' => '.env.production template',
    'status' => file_exists(__DIR__ . '/.env.production'),
    'message' => file_exists(__DIR__ . '/.env.production') ? 'Found' : 'Missing - create .env.production'
];

// Check composer.json
$composerJson = json_decode(file_get_contents(__DIR__ . '/composer.json'), true);
$phpVersion = $composerJson['require']['php'] ?? '0';
$hasRequiredPHP = strpos($phpVersion, '8.1') !== false || strpos($phpVersion, '^8.1') !== false;
$checks[] = [
    'name' => 'PHP version compatibility',
    'status' => $hasRequiredPHP,
    'message' => $hasRequiredPHP ? 'PHP 8.1+ required ✓' : 'Update PHP requirement to ^8.1'
];

// Check for Laravel version
$hasLaravelV10 = isset($composerJson['require']['laravel/framework']) &&
    strpos($composerJson['require']['laravel/framework'], '^10') !== false;
$checks[] = [
    'name' => 'Laravel version',
    'status' => $hasLaravelV10,
    'message' => $hasLaravelV10 ? 'Laravel 10.x ✓' : 'Laravel 10.x recommended'
];

// Check package.json for build script
$packageJson = json_decode(file_get_contents(__DIR__ . '/package.json'), true);
$hasBuildScript = isset($packageJson['scripts']['vercel-build']);
$checks[] = [
    'name' => 'Build script',
    'status' => $hasBuildScript,
    'message' => $hasBuildScript ? 'vercel-build script found ✓' : 'Build script configured ✓'
];

// Display results
foreach ($checks as $check) {
    $status = $check['status'] ? '✅ PASS' : '❌ FAIL';
    echo sprintf("%-30s %s - %s\n", $check['name'], $status, $check['message']);
}

echo "\n";

// Environment variables checklist
echo "📋 Environment Variables Checklist:\n";
echo "==================================\n";
$envVars = [
    'APP_NAME' => 'Application name',
    'APP_ENV' => 'Should be: production',
    'APP_KEY' => 'Generate with: php artisan key:generate',
    'APP_DEBUG' => 'Should be: false',
    'APP_URL' => 'Your Vercel app URL',
    'DB_CONNECTION' => 'Database type (mysql recommended)',
    'DB_HOST' => 'Database host',
    'DB_DATABASE' => 'Database name',
    'DB_USERNAME' => 'Database username',
    'DB_PASSWORD' => 'Database password'
];

foreach ($envVars as $var => $description) {
    echo sprintf("- %-15s : %s\n", $var, $description);
}

echo "\n🔧 Pre-deployment Commands:\n";
echo "===========================\n";
echo "1. composer install --no-dev --optimize-autoloader\n";
echo "2. npm install && npm run build\n";
echo "3. vercel --prod\n";

echo "\n✨ Ready for deployment! Run 'vercel --prod' to deploy.\n";