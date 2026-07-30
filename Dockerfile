# 1. Usar PHP 8.4 FPM
FROM php:8.4-fpm

# 2. Instalar dependencias del sistema y Nginx
RUN apt-get update && apt-get install -y \
    nginx \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Instalar extensiones de PHP necesarias para Laravel
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# 4. Obtener Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Configurar directorio de trabajo
WORKDIR /var/www/html

# 6. Copiar los archivos del proyecto al contenedor
COPY . /var/www/html

# 7. Instalar dependencias de Composer sin entornos de desarrollo
RUN composer install --no-dev --optimize-autoloader

# 8. Copiar la configuración de Nginx
COPY ./docker/nginx/default.conf /etc/nginx/sites-available/default
RUN rm -f /etc/nginx/sites-enabled/default \
    && ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# 9. Asignar permisos correctos a carpetas de almacenamiento de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 10. Exponer el puerto 80
EXPOSE 80

# 11. Arrancar PHP-FPM y Nginx juntos
CMD service php8.4-fpm start || php-fpm -D; nginx -g "daemon off;"
