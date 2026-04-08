FROM webdevops/php-nginx:8.2

WORKDIR /app

COPY . /app

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    nodejs \
    npm \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev

RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

ENV WEB_DOCUMENT_ROOT=/app/public

EXPOSE 8080