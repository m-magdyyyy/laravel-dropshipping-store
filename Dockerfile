FROM php:8.2-cli

# Install system dependencies required to build PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        ca-certificates \
        zip \
        unzip \
        git \
        curl \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libonig-dev \
        libxml2-dev \
        default-mysql-client \
        gnupg2 \
        build-essential \
        libicu-dev \
    && rm -rf /var/lib/apt/lists/*

# Configure and install the required PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql mbstring bcmath exif gd \
    && docker-php-ext-install -j$(nproc) zip

# Install Composer from the official composer image (2.7)
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files first to leverage Docker cache
COPY composer.json composer.lock /app/

# Install PHP dependencies (no dev, optimized autoloader, no interaction, no scripts)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy the rest of the application
COPY . /app

# Create storage directories and set permissions
RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Expose port and default port env used by Render
ENV PORT=8000
EXPOSE 8000

# On container start: attempt migrations (allow failure) then run PHP built-in server serving from public/
CMD ["sh", "-lc", "php artisan migrate --force || true; php -S 0.0.0.0:$PORT -t public public/index.php"]
