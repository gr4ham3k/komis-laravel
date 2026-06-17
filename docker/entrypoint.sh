#!/bin/sh

set -e

if [ ! -f "/var/www/.env" ]; then
  echo "Creating .env from .env.example..."
  cp /var/www/.env.example /var/www/.env
fi

if [ ! -d "/var/www/vendor" ]; then
  echo "Installing Composer dependencies..."
  composer install --no-interaction --optimize-autoloader
fi

if ! grep -q "APP_KEY=" /var/www/.env || [ "$(grep 'APP_KEY=' /var/www/.env | cut -d= -f2)" = "" ]; then
  echo "Generating app key..."
  php artisan key:generate --force
fi

echo "Waiting for PostgreSQL..."
while ! nc -z db 5432 2>/dev/null; do sleep 1; done
echo "DB ready!"

php artisan migrate --force
php artisan db:seed --force

php artisan storage:link --force 2>/dev/null || true

php artisan config:cache && php artisan route:cache && php artisan view:cache

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
