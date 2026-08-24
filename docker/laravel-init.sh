#!/bin/sh
set -e

echo "🚀 Running Laravel init..."

# Wait for database
echo "⏳ Waiting for database..."
RETRIES=30
until php /var/www/html/artisan db:show > /dev/null 2>&1; do
    RETRIES=$((RETRIES - 1))
    if [ $RETRIES -eq 0 ]; then
        echo "❌ Database not ready after 30 attempts"
        exit 1
    fi
    sleep 2
done
echo "✅ Database ready"

# Run migrations
echo "📦 Running migrations..."
php /var/www/html/artisan migrate --force

# Build caches
echo "🔧 Building caches..."
php /var/www/html/artisan config:cache
php /var/www/html/artisan route:cache
php /var/www/html/artisan view:cache

# Storage link
php /var/www/html/artisan storage:link 2>/dev/null || true

# Fix permissions
chown -R application:application /var/www/html/storage 2>/dev/null || true
chown -R application:application /var/www/html/bootstrap/cache 2>/dev/null || true

echo "✅ Laravel init complete"
