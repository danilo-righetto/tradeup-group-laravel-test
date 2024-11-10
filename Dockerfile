# Definição da imagem base para executar a aplicação
FROM php:8.2-apache

RUN apt-get -y update && \
    apt-get install -y \
    libssl-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    apache2 \
    software-properties-common \
    libmemcached-dev \
    libcurl4-openssl-dev \
    pkg-config \
    ca-certificates \
    supervisor


# Configurações do apache2
RUN a2enmod ssl rewrite headers proxy* && rm /etc/apache2/sites-enabled/000*

COPY webserver/app-apache.conf /etc/apache2/sites-enabled
COPY webserver/apache2.conf /etc/apache2/
COPY webserver/ports.conf /etc/apache2/
COPY webserver/php.ini /etc/php/8.2/apache2/


# Instalação dos módulos e extensões do PHP
RUN apt-get update && apt-get install gpg && echo -n 'deb http://ppa.launchpad.net/ondrej/php/ubuntu groovy main' > /etc/apt/sources.list.d/ondrej-ubuntu-php-groovy.list && \
    apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 14AA40EC0831756756D7F66C4F4EA0AAE5267A6C

RUN apt-get update && \
    pecl channel-update pecl.php.net && \
    pecl install zlib zip redis curl memcached-3.2.0 mysqli && \
    docker-php-ext-install curl mysqli pdo pdo_mysql && \
    docker-php-ext-enable zip redis curl memcached pdo_mysql

RUN update-ca-certificates


# Instalação do composer e suas dependências
WORKDIR /var/www/app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer clear-cache

COPY . /var/www/app

RUN rm -f composer.lock

RUN composer install

# Execução dos serviços e "levantar" a aplicação
RUN chmod +x /var/www/app/webserver/entrypoint.sh
RUN rm -r webserver/*.conf && rm -r webserver/*.ini

# RUN php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"

#RUN php artisan l5-swagger:generate
#RUN cp storage/api-docs/api-docs.json public/swagger/

RUN php artisan key:generate

EXPOSE 80

ENTRYPOINT ["/var/www/app/webserver/entrypoint.sh"]
CMD ["supervisord", "-c", "/var/www/app/supervisord.conf"]
