# Usa una imagen base de PHP con FPM
FROM php:7.4-fpm

# Instala extensiones de PHP necesarias
RUN apt-get update && apt-get install -y \
    zip unzip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mysqli pdo pdo_mysql mbstring

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copia los archivos de la aplicación
COPY . /var/www/html

# Asigna permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Define el directorio de trabajo
WORKDIR /var/www/html

# Copia el script de entrada
COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Exponer el puerto del servidor de desarrollo
EXPOSE 8000

# Comando de inicio
ENTRYPOINT ["docker-entrypoint.sh"]
