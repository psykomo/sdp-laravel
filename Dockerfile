FROM php:8.4-fpm AS base

ARG UID=1000
ARG GID=1000

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    supervisor \
    pkg-config \
    g++ \
    libicu-dev \
    libpng-dev \
    libpq-dev \
    libzip-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) pdo_mysql pdo_pgsql bcmath intl zip gd \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Fix git safe.directory issue
RUN git config --global --add safe.directory /var/www/html

# Create laravel user
RUN addgroup --gid ${GID} laravel \
    && adduser --uid ${UID} --gid ${GID} --disabled-password --gecos "" laravel \
    && chown -R laravel:laravel /var/www/html

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="/var/www/html/vendor/bin:${PATH}"

# Copy application files
COPY --chown=laravel:laravel . .

# Install dependencies and build assets
RUN composer install --optimize-autoloader --no-interaction \
    && npm ci \
    && npm run build \
    && rm -rf node_modules \
    && chown -R laravel:laravel /var/www/html

USER laravel

CMD ["php-fpm"]
