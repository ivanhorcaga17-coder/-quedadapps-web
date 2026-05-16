FROM dunglas/frankenphp:1.2-php8.4

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y \
    curl \
    git \
    libonig-dev \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    zip \
    && docker-php-ext-install mbstring pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

RUN curl -fsSL https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY composer.json composer.lock ./

COPY package.json package-lock.json ./

COPY . .

RUN rm -f bootstrap/cache/*.php public/hot \
    && rm -rf public/storage \
    && composer install --no-dev --optimize-autoloader --no-interaction \
    && npm install \
    && mkdir -p public/build storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && php artisan storage:link --no-interaction \
    && npm run build \
    && test -f public/build/manifest.json \
    && chown -R www-data:www-data storage bootstrap/cache public/build public/storage \
    && chmod -R ug+rwx storage bootstrap/cache

EXPOSE 8080

CMD ["frankenphp", "run", "--config=/app/Caddyfile"]

# force rebuild
