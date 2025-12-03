#!/bin/bash

# Vercel Build Script for Laravel
echo "Starting Laravel build process for Vercel..."

# Install composer dependencies
echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Generate application key if not exists
if [ ! -f .env ]; then
    cp .env.production .env
    php artisan key:generate --force
fi

# Cache configurations for better performance
echo "Caching configurations..."
php artisan config:cache || echo "Config cache skipped"
php artisan route:cache || echo "Route cache skipped"  
php artisan view:cache || echo "View cache skipped"

# Build frontend assets
echo "Building frontend assets..."
npm ci
npm run build

echo "Laravel build process completed!"