#!/bin/sh
set -e

echo "🚀 Starting Nuwesoft production container..."

# Wait for database
echo "⏳ Waiting for database..."
until php /var/www/html/artisan db:show > /dev/null 2>&1; do
    sleep 2
done
echo "✅ Database ready"

# Run migrations
echo "📦 Running migrations..."
php /var/www/html/artisan migrate --force

# Clear and rebuild caches
echo "🔧 Building caches..."
php /var/www/html/artisan config:cache
php /var/www/html/artisan route:cache
php /var/www/html/artisan view:cache

# Storage link
php /var/www/html/artisan storage:link 2>/dev/null || true

# Set permissions
chown -R application:application /var/www/html/storage 2>/dev/null || true
chown -R application:application /var/www/html/bootstrap/cache 2>/dev/null || true

echo "✅ Application ready"

# Start supervisord (handles nginx + php-fpm + queue workers)
exec /usr/bin/supervisord -c /etc/supervisord.conf
