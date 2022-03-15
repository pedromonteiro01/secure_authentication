FROM php:7.4-apache

WORKDIR /var/www/html

COPY . .

RUN chmod 777 auth_counter.txt
RUN chmod 777 global_variables.txt

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN apt-get update && apt-get upgrade -y