FROM php:8.2-fpm
ARG CACHEBUST=1

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    curl \
    zip \
    unzip \
    git \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

WORKDIR /app

# Copiar proyecto
COPY . .

# Instalar dependencias de PHP
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# DEBUG: Ver qué archivos existen en /app
RUN ls -R /app

# Instalar dependencias de Node y compilar Vite
RUN rm -rf node_modules package-lock.json
RUN npm install && npm run build

# Instalar FrankenPHP
RUN mkdir -p /app/bin && \
    curl -L https://github.com/dunglas/frankenphp/releases/latest/download/frankenphp-linux-x86_64 -o /app/bin/frankenphp && \
    chmod +x /app/bin/frankenphp

EXPOSE 8080

# DEBUG: Ver qué hay en public después del build
RUN ls -R /app/public

CMD ["/app/bin/frankenphp", "run", "--config", "/app/Caddyfile"]
