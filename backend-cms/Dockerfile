FROM php:8.5-fpm-alpine

# Instalar dependencias del sistema y librerías de desarrollo
RUN apk add --no-cache \
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

# Configurar e instalar extensiones nativas de PHP
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
