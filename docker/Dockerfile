ARG PHP_VERSION=8.4

FROM php:${PHP_VERSION}-fpm-alpine

# Arguments
ARG UID=1000
ARG GID=1000

# Installation des dépendances système
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    oniguruma-dev \
    autoconf \
    g++ \
    make \
    openssl-dev \
    pcre-dev \
    postgresql-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    icu-dev \
    linux-headers

# Installation des extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    opcache \
    soap \
    sockets

# Installation de Redis
RUN pecl install redis \
    && docker-php-ext-enable redis

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuration PHP
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini

# Création de l'utilisateur
RUN addgroup -g ${GID} --system laravel \
    && adduser -G laravel --system -D -s /bin/sh -u ${UID} laravel

# Définition du répertoire de travail
WORKDIR /var/www

# Permissions
RUN chown -R laravel:laravel /var/www

# Changement d'utilisateur
USER laravel

# Exposition du port
EXPOSE 9000

CMD ["php-fpm"]
