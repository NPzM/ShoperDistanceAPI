FROM php:7.4-apache

WORKDIR /var/www/html

RUN a2enmod rewrite && \
    apt update && apt install -y libicu-dev unzip git libcurl4-openssl-dev wget curl &&\
    docker-php-ext-install mysqli curl;

RUN wget https://getcomposer.org/installer -O - | php -- --install-dir=/usr/local/bin --filename=composer --version=2.1.6

COPY docker/php/php.ini /usr/local/etc/php/conf.d/php.ini

RUN usermod -u 1000 www-data

COPY . .

RUN composer install --no-dev --optimize-autoloader && \
    rm -rf $(cat .build_exclude) && \
    rm /usr/local/bin/composer && \
    rm -rf /root/.ssh && \
    apt remove -y wget git && \
    apt autoremove -y

RUN chown -R www-data:www-data .
