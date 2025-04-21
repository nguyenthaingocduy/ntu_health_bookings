#!/bin/bash

# Deployment script for NTU Health Booking System
# This script automates the deployment process

echo "Starting deployment process..."

# Pull the latest code from the repository
echo "Pulling latest code..."
git pull

# Install/update dependencies
echo "Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Compile assets
echo "Compiling assets..."
npm ci
npm run build

# Clear all caches
echo "Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Optimize for production
echo "Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Test email configuration
echo "Testing email configuration..."
php artisan email:test-production

echo "Deployment completed successfully!"
echo "Please check the logs for any errors."
