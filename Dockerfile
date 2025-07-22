FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Install dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Install npm dependencies and build assets (with error handling)
RUN if [ -f "package.json" ]; then \
    npm install --no-audit --no-fund --no-optional && \
    npm run build || echo "Frontend build failed, but continuing deployment" \
    ; fi

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copy render environment file and run build script when deploying
COPY .env.render /var/www/.env.render
COPY render-build.sh /var/www/render-build.sh
RUN chmod +x /var/www/render-build.sh

# Cache configuration
RUN php artisan config:cache
RUN php artisan route:cache

# Expose port 8000
EXPOSE 8000

# Create startup script
RUN echo '#!/bin/bash\n
# Ensure environment variables are loaded\ncp .env.render .env\n
# Clear config cache\nphp artisan config:clear\n
# Run migrations with debug output\nphp artisan migrate --force --verbose\n
# Start server\nexec php artisan serve --host=0.0.0.0 --port=8000' > /var/www/start.sh \
    && chmod +x /var/www/start.sh

# Use the startup script
CMD ["/var/www/start.sh"]
