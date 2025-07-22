#!/usr/bin/env bash
echo "Running composer"

composer install --no-dev --working-dir=/var/www/html

echo "Generating application key..."
php artisan key:generate --show

# Clear config cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "Publishing cloudinary provider..."
php artisan vendor:publish --provider="CloudinaryLabs\CloudinaryLaravel\CloudinaryServiceProvider" --tag="cloudinary-laravel-config"

echo "Run migrations fresh"
php artisan migrate:fresh --force --verbose

echo "Seed the database (only if tables are empty to avoid duplicates)"
echo "Checking if database needs seeding..."
php artisan db:seed --force --verbose

# Start PHP-FPM server
echo "Starting PHP-FPM server..."
exec php-fpm
