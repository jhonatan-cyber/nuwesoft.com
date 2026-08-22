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

# Configure queue workers count
QUEUE_WORKERS=${QUEUE_WORKERS:-1}
echo "Configurando $QUEUE_WORKERS queue worker(s)..."
sed -i "s/numprocs=1/numprocs=$QUEUE_WORKERS/" /opt/docker/etc/supervisor.d/laravel-workers.conf 2>/dev/null || true

echo "=== Bootstrap completado. Levantando Nginx y PHP-FPM ==="

# Ejecutar el entrypoint original de la imagen base webdevops
if [ $# -eq 0 ]; then
    exec /entrypoint supervisord
else
    exec /entrypoint "$@"
fi
