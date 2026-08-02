# 1. Usar PHP 8.4 FPM sobre Debian Slim
FROM php:8.4-fpm

# 2. Instalar dependencias del sistema, Nginx y Python 3
RUN apt-get update && apt-get install -y \
    nginx \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    python3 \
    python3-pip \
    python3-venv \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Instalar extensiones de PHP requeridas por Laravel
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# 4. Obtener Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Configurar directorio de trabajo
WORKDIR /var/www/html

# 6. Copiar los archivos del proyecto
COPY . /var/www/html

# 7. Instalar dependencias de Composer para producción
RUN composer install --no-dev --optimize-autoloader

# 8. Instalar librerías de Python Ultraligeras (Precompiladas en Binario)
# Usamos numpy + scikit-learn + pymysql sin cargar pandas para no agotar la RAM de Render Gratis
RUN pip3 install --no-cache-dir --only-binary=:all: --break-system-packages \
    numpy \
    scikit-learn \
    pymysql

# 9. Configuración de Nginx
COPY ./docker/nginx/default.conf /etc/nginx/sites-available/default
RUN rm -f /etc/nginx/sites-enabled/default \
    && ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# 10. Permisos a las carpetas de almacenamiento de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 11. Exponer el puerto 80
EXPOSE 80

# 12. Arrancar PHP-FPM y Nginx
CMD service php8.4-fpm start || php-fpm -D; nginx -g "daemon off;"