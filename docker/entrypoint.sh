#!/bin/sh
set -e
cd /var/www/html

# Writable runtime folders (they may be empty when /storage is a mounted volume).
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views \
         storage/logs storage/app/public bootstrap/cache
[ -f .env ] || cp .env.example .env

# SQLite file (default). Point DB_DATABASE at a mounted volume to persist it.
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
  DB_FILE="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
  mkdir -p "$(dirname "$DB_FILE")"
  touch "$DB_FILE"
  export DB_DATABASE="$DB_FILE"
  chown www-data:www-data "$DB_FILE" "$(dirname "$DB_FILE")"
fi

# Generate an APP_KEY once and keep it with the persisted storage.
if [ -z "$APP_KEY" ]; then
  KEYFILE=storage/app/.app_key
  [ -s "$KEYFILE" ] || php artisan key:generate --show --no-interaction > "$KEYFILE"
  export APP_KEY="$(cat "$KEYFILE")"
fi

chown -R www-data:www-data storage bootstrap/cache

php artisan storage:link --no-interaction >/dev/null 2>&1 || true
php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan view:cache --no-interaction

# Platforms such as Render / Railway provide $PORT.
if [ -n "$PORT" ]; then
  sed -i "s/Listen 80/Listen ${PORT}/; s/:80>/:${PORT}>/" /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf
fi

exec "$@"
