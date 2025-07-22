#!/usr/bin/env bash
# Exit on error but with full output for debugging
set -x

echo "===== Starting Render build script ====="

# Copy the render environment file
echo "Copying environment file..."
cp .env.render .env

# Set proper permissions
echo "Setting file permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || echo "Warning: Could not change ownership (this is normal in some environments)"

# Clear cached configuration
echo "Clearing configuration cache..."
php artisan config:clear
php artisan cache:clear

# Output key environment variables for debugging (without values)
echo "Checking environment variables..."
env | grep -E "^(DB_|APP_)" | sed 's/=.*/=***/' 

# Try to connect to database
echo "Testing database connection..."
php artisan db:monitor || echo "Database connection test failed (continuing)"

# Create storage link if needed
echo "Creating storage link..."
php artisan storage:link || echo "Storage link creation skipped"

# Generate app key if not set
echo "Generating application key..."
php artisan key:generate --force

# Run migrations with verbose output
echo "Running database migrations fresh..."
php artisan migrate:fresh --force --verbose

# Seed the database
echo "Seeding database render build sh..."
php artisan db:seed --force --verbose

# Verify migrations and seeding ran
echo "Verifying migrations and seeding..."
php debug-db.php

echo "===== Render build script completed ====="
