#!/bin/sh

set -e

echo "Waiting for PostgreSQL..."

while ! nc -z db 5432; do
  sleep 1
done

echo "DB ready!"

php artisan migrate --force

if [ ! -f "/var/www/.seeded" ]; then
  php artisan db:seed --force
  touch /var/www/.seeded
fi

php artisan storage:link || true

php artisan serve --host=0.0.0.0 --port=8000
