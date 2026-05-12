FROM dunglas/frankenphp:1.2-php8.2

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    curl \
    zip \
    unzip \
    git \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

WORKDIR /app

# Copiar proyecto
COPY . .

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Instalar dependencias Node y compilar Vite
RUN rm -rf node_modules package-lock.json
RUN npm install
RUN npm run build

# Permisos correctos
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

EXPOSE 8080

CMD ["php", "artisan", "frankenphp:serve", "--host=0.0.0.0", "--port=8080"]
