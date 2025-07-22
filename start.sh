#!/bin/bash

# Ensure environment variables are loaded
cp .env.render .env

# Clear config cache
php artisan config:clear

# Run migrations with debug output
php artisan migrate --force --verbose

# Seed the database (only if tables are empty to avoid duplicates)
echo "Checking if database needs seeding..."
php artisan db:seed --force --verbose

# Start server
exec php artisan serve --host=0.0.0.0 --port=8000
