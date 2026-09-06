#!/bin/bash

echo "========================================="
echo "🚀 STARTING DEPLOYMENT"
echo "========================================="

# 1. Pull dari repository
echo "📥 Pulling from repository..."
git pull origin main

# 2. Hapus cache route yang lama
echo "🗑️ Menghapus cache route lama..."
rm -rf bootstrap/cache/*

# 3. Clear semua cache
echo "🧹 Clearing caches..."
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan optimize:clear
php artisan clear-compiled

# 4. Re-generate autoload
echo "📦 Regenerating autoload..."
composer dump-autoload

# 5. Cache ulang (opsional)
echo "⚡ Caching routes..."
php artisan route:cache
php artisan view:cache

# 6. Migrate database
echo "🗄️ Running migrations..."
php artisan migrate --force

# 7. Set permission
echo "🔑 Setting permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# 8. Restart services
echo "🔄 Restarting services..."
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx

echo "========================================="
echo "✅ DEPLOYMENT COMPLETED SUCCESSFULLY!"
echo "========================================="