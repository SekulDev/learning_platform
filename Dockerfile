FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash -
RUN apt-get install -y nodejs

# Install pnpm
RUN corepack enable
RUN corepack prepare pnpm@latest --activate

WORKDIR /var/www/html

# Copy existing application directory contents
COPY ./ /var/www/html

# Copy composer.json and composer.lock
COPY composer.json composer.lock ./

# Install composer dependencies
RUN composer install --no-scripts --no-autoloader

# Copy the rest of the application code
COPY ./ .

ARG LARAVEL_ENV_FILE_NAME=".env"

# Generate the autoloader
RUN composer dump-autoload --optimize

# Install pnpm dependencies and build assets
COPY package.json pnpm-lock.yaml ./
RUN pnpm install
RUN pnpm run build

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Configure PHP
COPY deploy/php.ini /usr/local/etc/php/conf.d/local.ini

COPY ./${LARAVEL_ENV_FILE_NAME} ./.env

# Expose port 8000
EXPOSE 8000

CMD php artisan serve --port 8000
