#!/bin/bash
set -e

PORT=${PORT:-80}
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

php artisan storage:link || true

if [ "$RUN_MIGRATIONS" = "true" ]; then
    php artisan migrate --force || true
fi

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

exec apache2-foreground
