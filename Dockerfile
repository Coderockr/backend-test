FROM php:8.0-fpm-alpine

#COPY composer.lock composer.json /var/www/

WORKDIR /var/www

# Install dependencies
RUN apk add --no-cache \
      freetype \
      libjpeg-turbo \
      libpng \
      freetype-dev \
      libjpeg-turbo-dev \
      libpng-dev \
    && docker-php-ext-configure gd \
      --with-freetype=/usr/include/ \
      # --with-png=/usr/include/ \ # No longer necessary as of 7.4; https://github.com/docker-library/php/pull/910#issuecomment-559383597
      --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
   && docker-php-ext-enable gd \
    && apk del --no-cache \
      freetype-dev \
      libjpeg-turbo-dev \
      libpng-dev \
    && rm -rf /tmp/*



RUN apk add libzip-dev

RUN docker-php-ext-install pdo pdo_mysql zip bcmath

# Clear cache
#RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
#RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
#RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
#RUN docker-php-ext-install gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel
#RUN groupadd -g 1000 www && adduser -G laravel -g laravel -s /bin/sh -D laravel
RUN addgroup -g 1000 www && adduser -G www -g www -s /bin/sh -D www





# Copy application folder
COPY . /var/www

# Copy existing permissions from folder to docker
COPY --chown=www:www . /var/www
RUN chown -R www-data:www-data /var/www
#RUN chmod -R 777 /var/www/uploads
#RUN chmod -R 777 /var/www/uploads/thumbnail



#RUN chown -R www-data:www-data /var/www
#RUN chmod -R 755 /var/www/storage
#RUN chmod -R 755 /var/www/storage/logs



# change current user to www
USER www

EXPOSE 9000
CMD ["php-fpm"]
