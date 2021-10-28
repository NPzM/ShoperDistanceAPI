FROM php:7.4-apache

WORKDIR /var/www/html

RUN a2enmod rewrite && \
    apt update && apt install -y libicu-dev unzip git wget curl

RUN wget https://getcomposer.org/installer -O - | php -- --install-dir=/usr/local/bin --filename=composer --version=2.1.6

COPY . .

RUN composer install --optimize-autoloader && \
    rm -rf $(cat .build_exclude) && \
    rm /usr/local/bin/composer && \
    rm -rf /root/.ssh && \
    apt remove -y wget git && \
    apt autoremove -y
