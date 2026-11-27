# ---------- 1) Front-end assets (Vite + Tailwind) ----------
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY vite.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm run build

# ---------- 2) PHP dependencies ----------
# Plain PHP image + the composer binary (avoids crashes of the composer:2 image).
# PHP 8.4 is required: the project's vendor/ needs PHP >= 8.4.1.
FROM php:8.4-cli AS vendor
RUN apt-get update \
 && apt-get install -y --no-install-recommends git unzip libzip-dev libsqlite3-dev \
 && docker-php-ext-install zip bcmath pdo_sqlite pdo_mysql \
 && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1 COMPOSER_MEMORY_LIMIT=-1
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction
COPY . .
# Never ship host-side discovery caches: they may reference dev-only packages (e.g. Pail).
RUN rm -f bootstrap/cache/*.php \
 && composer dump-autoload --optimize --no-dev --no-scripts

# ---------- 3) Runtime ----------
FROM php:8.4-apache
RUN apt-get update \
 && apt-get install -y --no-install-recommends libzip-dev libsqlite3-dev unzip \
 && docker-php-ext-install pdo_mysql pdo_sqlite zip bcmath opcache \
 && a2enmod rewrite headers \
 && rm -rf /var/lib/apt/lists/*

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
      /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html
COPY --from=vendor /app ./
COPY --from=assets /app/public/build ./public/build
COPY docker/entrypoint.sh /usr/local/bin/entrypoint
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint \
 && chmod +x /usr/local/bin/entrypoint \
 && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs storage/app/public bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
ENTRYPOINT ["entrypoint"]
CMD ["apache2-foreground"]
