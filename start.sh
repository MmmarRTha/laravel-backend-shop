#!/usr/bin/env bash
set -e  # Exit on any error

echo "Running composer"
composer install --no-dev --working-dir=/var/www/html

echo "Checking application key..."
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "No APP_KEY found, generating new one..."
    php artisan key:generate --force
else
    echo "APP_KEY already set"
fi

# Clear config cache
echo "Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Test database connection
echo "Testing database connection..."
php artisan tinker --execute="echo 'DB connection: ' . DB::connection()->getPdo() ? 'OK' : 'FAILED';"

echo "Publishing cloudinary provider..."
php artisan vendor:publish --provider="CloudinaryLabs\CloudinaryLaravel\CloudinaryServiceProvider" --tag="cloudinary-laravel-config" || echo "Cloudinary publish failed, continuing..."

echo "Running migrations..."
php artisan migrate --force --verbose

echo "Seeding database..."
php artisan db:seed --force --verbose || echo "Seeding failed, continuing..."

# Start Laravel development server
echo "Starting Laravel development server..."
exec php artisan serve --host=0.0.0.0 --port=8000
