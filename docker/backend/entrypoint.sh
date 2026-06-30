#!/bin/sh
set -e

cd /var/www/html

if [ ! -f "composer.json" ]; then
    echo "Laravel composer.json not found. Starting container command."
    exec "$@"
fi

if [ ! -d "vendor" ]; then
    echo "Installing PHP dependencies with Composer..."
    composer install --no-interaction --prefer-dist
else
    echo "PHP dependencies already installed."
fi

if [ ! -f ".env" ] && [ -f ".env.example" ]; then
    echo "Creating Laravel .env from .env.example..."
    cp .env.example .env
fi

if [ -f "artisan" ] && [ -f ".env" ]; then
    APP_KEY_VALUE="$(grep '^APP_KEY=' .env | cut -d '=' -f2- || true)"

    if [ -z "$APP_KEY_VALUE" ]; then
        echo "Generating Laravel application key..."
        php artisan key:generate --force --no-interaction
    else
        echo "Laravel application key already configured."
    fi
fi

exec "$@"