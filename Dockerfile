FROM php:8.3-cli

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . /var/www/html

# Copy .env.render as .env for production
COPY .env.render /var/www/html/.env

# Make start script executable
RUN chmod +x /var/www/html/start.sh

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config (these can be overridden by .env file)
# ENV APP_ENV production
# ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow composer to run as root (important for containerized environments)
ENV COMPOSER_ALLOW_SUPERUSER 1

# Install npm and build assets if your API serves any front-end assets (optional)
# RUN apt-get install -y nodejs npm && \
#    npm install -g npm@latest

# Expose port 8000
EXPOSE 8000

CMD ["/var/www/html/start.sh"]
