#!/bin/sh
set -e

# Crear .env si no existe a partir del ejemplo
if [ ! -f /var/www/html/.env ]; then
    if [ -f /var/www/html/.env.example ]; then
        echo "Creating .env file from .env.example..."
        cp /var/www/html/.env.example /var/www/html/.env
    fi
fi

# Configurar variables de base de datos en .env según el contenedor
if [ -f /var/www/html/.env ]; then
    # Forzar conexión MySQL del contenedor
    sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=mysql/' /var/www/html/.env || echo "DB_CONNECTION=mysql" >> /var/www/html/.env
    sed -i 's/^DB_HOST=.*/DB_HOST='${DB_HOST:-db}'/' /var/www/html/.env || echo "DB_HOST=${DB_HOST:-db}" >> /var/www/html/.env
    sed -i 's/^DB_PORT=.*/DB_PORT='${DB_PORT:-3306}'/' /var/www/html/.env || echo "DB_PORT=${DB_PORT:-3306}" >> /var/www/html/.env
    sed -i 's/^DB_DATABASE=.*/DB_DATABASE='${DB_DATABASE:-deperumaster_cms}'/' /var/www/html/.env || echo "DB_DATABASE=${DB_DATABASE:-deperumaster_cms}" >> /var/www/html/.env
    sed -i 's/^DB_USERNAME=.*/DB_USERNAME='${DB_USERNAME:-root}'/' /var/www/html/.env || echo "DB_USERNAME=${DB_USERNAME:-root}" >> /var/www/html/.env
    sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD='${DB_PASSWORD:-1234}'/' /var/www/html/.env || echo "DB_PASSWORD=${DB_PASSWORD:-1234}" >> /var/www/html/.env

    # Generar APP_KEY si falta
    if ! grep -q "^APP_KEY=base64:" /var/www/html/.env; then
        echo "Generating Laravel APP_KEY..."
        php /var/www/html/artisan key:generate --force || true
    fi

    # Limpiar caché de configuración antigua
    php /var/www/html/artisan config:clear || true
fi

# Asegurar directorios de almacenamiento, subidas y caché
mkdir -p /var/www/html/storage/app/public \
         /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache

# Crear symlink de storage si no existe para servir archivos públicos
php /var/www/html/artisan storage:link || true

# Ajustar permisos para www-data
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

exec "$@"
