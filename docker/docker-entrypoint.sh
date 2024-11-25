#!/bin/bash

# Instalar dependencias con Composer
#if [ ! -d "vendor" ]; then
echo "Instalando dependencias con Composer..."
composer install
#fi

# Verificar y configurar el archivo .env
#if [ ! -f ".env" ]; then
#    echo "Creando archivo .env..."
cp .env.example .env
php artisan key:generate
#fi

# Esperar a que la base de datos esté lista
echo "Esperando a la base de datos..."
until php artisan migrate --force; do
    echo "Base de datos no está lista. Reintentando en 5 segundos..."
    sleep 5
done

# Iniciar el servidor de desarrollo
echo "Iniciando servidor de Laravel..."

php artisan jwt:secret

php artisan serve --host=0.0.0.0 --port=8000
