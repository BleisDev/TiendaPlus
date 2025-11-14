FROM php:8.2-apache

# Instalar extensiones necesarias para MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copiar tu proyecto dentro del contenedor
COPY ./web /var/www/html/

# Dar permisos correctos
RUN chown -R www-data:www-data /var/www/html
