#!/usr/bin/env bash
# Exit on error
set -e

# Copy the render environment file
cp .env.render .env

# Set proper permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Clear cached configuration
php artisan config:clear
php artisan cache:clear

# Create storage link if needed
php artisan storage:link || true

# Generate app key if not set
php artisan key:generate --force

# Run migrations
php artisan migrate --force
