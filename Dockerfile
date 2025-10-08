# Usar una imagen base oficial de PHP 8.3 FPM
FROM php:8.3-fpm

# Establecer el directorio de trabajo
WORKDIR /var/www

# Instalar dependencias del sistema y extensiones de PHP
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Instalar extensiones de PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Obtener la última versión de Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copiar el código de la aplicación
COPY . .

# Instalar dependencias de Composer
RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist

# Cambiar permisos de las carpetas de Laravel
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Exponer el puerto 8000 y ejecutar el servidor de Laravel
EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=8000
