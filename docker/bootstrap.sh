#!/bin/sh
set -e

echo "=== Iniciando bootstrap de producción para Laravel ==="

# Asegurar permisos correctos para storage y cache
echo "Configurando permisos en directorios de escritura..."
chmod -R 775 storage bootstrap/cache
chown -R application:application storage bootstrap/cache

# Cachear la configuración, rutas y vistas para rendimiento de producción
echo "Optimizando caché de Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Correr migraciones de base de datos de forma segura (sin confirmación interactiva)
echo "Ejecutando migraciones de base de datos..."
php artisan migrate --force

echo "=== Bootstrap completado. Levantando Nginx y PHP-FPM ==="

# Ejecutar el entrypoint original de la imagen base webdevops
exec /entrypoint.sh "$@"
