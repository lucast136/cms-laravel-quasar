FROM php:8.5.12-fpm-alpine AS base

# Actualizar paquetes base (incluyendo brotli-libs) e instalar dependencias del sistema
RUN apk update && apk upgrade --no-cache && apk add --no-cache \
    nginx \
    supervisor \
    curl \
    git \
    unzip \
    bash \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev

# Configurar e instalar extensiones nativas de PHP (OPcache ya viene integrado en PHP 8.5)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        bcmath \
        exif \
        pcntl \
        gd \
        zip \
        intl

# Permitir que PHP-FPM reciba variables de entorno de Docker
RUN echo "clear_env = no" >> /usr/local/etc/php-fpm.d/zz-docker.conf

# Copiar el binario oficial de Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de configuración para Nginx y Supervisor
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# ==============================================================================
# Stage: dev (conserva dependencias de desarrollo para pruebas/debug local)
# ==============================================================================
FROM base AS dev

COPY . /var/www/html
RUN composer install --no-interaction --prefer-dist

RUN mkdir -p /var/www/html/storage/framework/cache/data \
             /var/www/html/storage/framework/sessions \
             /var/www/html/storage/framework/views \
             /var/www/html/storage/logs \
             /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# ==============================================================================
# Stage: prod (por defecto para staging/development y producción)
# ==============================================================================
FROM base AS prod

# Configuración optimizada de OPcache para producción
RUN { \
    echo "opcache.enable=1"; \
    echo "opcache.memory_consumption=128"; \
    echo "opcache.interned_strings_buffer=16"; \
    echo "opcache.max_accelerated_files=10000"; \
    echo "opcache.validate_timestamps=0"; \
    } > /usr/local/etc/php/conf.d/opcache.ini

# Copiar archivos del proyecto Laravel
COPY . /var/www/html

# Instalar dependencias de producción de Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Ajustar permisos para almacenamiento y caché
RUN mkdir -p /var/www/html/storage/framework/cache/data \
             /var/www/html/storage/framework/sessions \
             /var/www/html/storage/framework/views \
             /var/www/html/storage/logs \
             /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
