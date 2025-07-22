#!/usr/bin/env bash
echo "Running composer"

composer install --no-dev --working-dir=/var/www/html

# Clear config cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "Publishing cloudinary provider..."
php artisan vendor:publish --provider="CloudinaryLabs\CloudinaryLaravel\CloudinaryServiceProvider" --tag="cloudinary-laravel-config"