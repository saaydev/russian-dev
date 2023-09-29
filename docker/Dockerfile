FROM php:8.1-fpm
WORKDIR /var/www/html
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer
COPY ./backend ./
RUN composer install
RUN chmod 777 -R /var/www/html/storage
