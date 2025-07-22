#!/usr/bin/env bash
# Exit on error
set -e

# Copy the render environment file
cp .env.render .env

# Create storage link if needed
php artisan storage:link

# Run migrations
php artisan migrate --force
