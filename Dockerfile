FROM ghcr.io/railwayapp/nixpacks:ubuntu-1745885067

WORKDIR /app

# Copiar todo el proyecto
COPY . .

# Instalar dependencias del sistema
RUN nix-env -iA nixpkgs.php \
    nixpkgs.phpPackages.composer \
    nixpkgs.nodejs_22 \
    nixpkgs.php82Extensions.pdo_mysql \
    nixpkgs.php82Extensions.mbstring \
    nixpkgs.php82Extensions.curl \
    nixpkgs.php82Extensions.fileinfo \
    nixpkgs.php82Extensions.openssl \
    nixpkgs.php82Extensions.tokenizer \
    nixpkgs.php82Extensions.xml \
    nixpkgs.php82Extensions.dom \
    nixpkgs.php82Extensions.session \
    nixpkgs.curl

# Instalar FrankenPHP
RUN mkdir -p /app/bin && \
    curl -L https://github.com/dunglas/frankenphp/releases/latest/download/frankenphp-linux-x86_64 -o /app/bin/frankenphp && \
    chmod +x /app/bin/frankenphp

# Instalar dependencias de Node y compilar Vite
RUN npm ci && npm run build

# Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# Exponer puerto
EXPOSE 8080

# Comando de arranque
CMD ["/app/bin/frankenphp", "run", "--config", "/app/Caddyfile"]
