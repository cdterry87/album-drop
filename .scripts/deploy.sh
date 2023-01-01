#!/bin/sh

set -e

echo "Deploying application ..."

# Enter maintenance mode
(php artisan down) || true

# Update codebase
git fetch origin prod
git reset --hard origin/prod

# Install dependencies based on lock file
composer install --no-interaction --prefer-dist --optimize-autoloader

# Clear the old cache
php artisan clear-compiled

# Recreate cache
php artisan optimize

# Compile npm assets
npm run build

# Clear caches
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Migrate database
php artisan migrate --force

# Exit maintenance mode
php artisan up

echo "Application deployed!"
