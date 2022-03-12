FROM ubuntu:14.04

LABEL maintainer="Mahmoud Zalt <mahmoud@zalt.me>"

RUN apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0x5a16e7281be7a449

RUN apt-get update -y \
    && apt-get install -y software-properties-common wget \
    && wget -O - http://dl.hhvm.com/conf/hhvm.gpg.key | sudo apt-key add - \
    && add-apt-repository "deb http://dl.hhvm.com/ubuntu $(lsb_release -sc) main" \
    && apt-get update -y \
    && apt-get install -y hhvm \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /var/www

COPY server.ini /etc/hhvm/server.ini

RUN usermod -u 1000 www-data

WORKDIR /var/www

CMD ["/usr/bin/hhvm", "-m", "server", "-c", "/etc/hhvm/server.ini"]

EXPOSE 9000
