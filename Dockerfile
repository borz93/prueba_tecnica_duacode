FROM php:8.1-apache

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zlib1g-dev \
    libicu-dev \
    g++ \
    && docker-php-ext-install pdo_mysql mbstring \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && a2enmod rewrite

# Habilitar mod_rewrite y configurar AllowOverride
RUN a2enmod rewrite && \
    sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

COPY ./app /var/www/html

EXPOSE 80