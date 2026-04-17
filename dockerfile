FROM webdevops/php-nginx:8.2

WORKDIR /app

COPY . /app
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install system deps
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# Install PHP extensions for PostgreSQL
RUN docker-php-ext-install pdo_pgsql pgsql

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install frontend dependencies
RUN npm install
RUN npm run build

# Laravel folders
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

RUN chown -R application:application /app

ENV WEB_DOCUMENT_ROOT=/app/public

EXPOSE 8080