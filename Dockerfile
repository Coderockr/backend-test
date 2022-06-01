FROM php:7.4-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    sed \
    supervisor && \
    docker-php-ext-install zip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Update Php Settings
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
 && sed -E -i -e 's/max_execution_time = 30/max_execution_time = 4800/' $PHP_INI_DIR/php.ini \
 && sed -E -i -e 's/max_input_time = 60/max_input_time = 2400/' $PHP_INI_DIR/php.ini \
 && sed -E -i -e 's/memory_limit = 128M/memory_limit = 5120M/' $PHP_INI_DIR/php.ini \
 && sed -E -i -e 's/post_max_size = 8M/post_max_size = 300M/' $PHP_INI_DIR/php.ini \
 && sed -E -i -e 's/upload_max_filesize = 2M/upload_max_filesize = 300M/' $PHP_INI_DIR/php.ini \
 && sed -E -i -e 's/output_buffering = 4096/output_buffering = 3e+8/' $PHP_INI_DIR/php.ini \
 && sed -E -i -e 's/odbc.defaultlrl = 4096/odbc.defaultlrl = 3e+8/' $PHP_INI_DIR/php.ini

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set working directory
WORKDIR /var/www

USER $user

CMD php-fpm; /usr/bin/supervisord;