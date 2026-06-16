#!/bin/sh

set -e

echo "Waiting for PostgreSQL..."

while ! nc -z db 5432; do
  sleep 1
done

echo "DB ready!"

if [ ! -f ".env" ]; then
  echo "Creating .env from .env.example..."
  cp .env.example .env
fi

if ! grep -q "^APP_KEY=base64" .env 2>/dev/null; then
  echo "Generating app key..."
  php artisan key:generate --force
fi

php artisan migrate --force

if [ ! -f ".seeded" ]; then
  echo "Seeding database..."
  php artisan db:seed --force
  touch .seeded
fi

php artisan storage:link --force || true

php artisan serve --host=0.0.0.0 --port=8000
