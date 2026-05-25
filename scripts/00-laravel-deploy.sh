#!/usr/bin/env bash

# Exit immediately if a command exits with a non-zero status
set -e

echo "🚀 Running deployment tasks..."

# Link storage folder to public directory if not already linked
echo "🔗 Linking storage..."
php artisan storage:link || echo "Storage already linked or failed to link."

# Run database migrations
echo "🗄️ Running migrations..."
php artisan migrate --force

# Optimize Laravel performance
echo "⚡ Caching config, routes, and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment tasks completed successfully!"
