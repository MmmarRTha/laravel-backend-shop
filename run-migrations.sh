#!/bin/bash
# This script runs migrations manually with verbose output

echo "Setting up environment..."
cp .env.render .env

echo "Clearing configuration cache..."
php artisan config:clear
php artisan cache:clear

echo "Checking database connection..."
php artisan db:monitor

echo "Running migrations with debugging information..."
php artisan migrate --force --verbose

echo "Migration complete. Checking table list..."
php debug-db.php
