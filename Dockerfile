FROM ubuntu:20.04

# Utilities
RUN dpkg --add-architecture i386 && \
    apt-get update -y && \
    DEBIAN_FRONTEND="noninteractive" apt-get install -y apt-transport-https software-properties-common build-essential unzip curl git vim apache2 php libapache2-mod-php php-pgsql php-dev php-xml php-curl php-soap php-mbstring php-gd php-intl php-xsl php-pear php7.4-zip python-dev wget java-common --no-install-recommends

# Change image time zone
ENV TZ 'America/Sao_Paulo'
RUN echo $TZ > /etc/timezone && \
    apt-get update && apt-get install -y tzdata && \
    rm /etc/localtime && \
    ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && \
    dpkg-reconfigure -f noninteractive tzdata && \
    apt-get clean

# Composer PHP
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php --install-dir=/usr/bin --filename=composer

# Virtualhost
RUN a2enmod rewrite
RUN a2enmod proxy_html
COPY ./VirtualHost.conf /etc/apache2/sites-available/000-default.conf

# Volume
RUN mkdir /var/www/html/backend
VOLUME ["/var/www/html/backend"]
WORKDIR /var/www/html/backend
COPY . .
