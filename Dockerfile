FROM php:8.2.3-fpm

RUN apt-get update \
	&& apt-get install -y \
	libxml2-dev \
	libzip-dev \
	zip \
	&& apt-get clean

RUN pecl install xdebug

RUN docker-php-ext-install \
	mysqli \
	pdo \
	pdo_mysql \
	intl \
	dom \
	xmlwriter \
	simplexml \
	zip \
	&& docker-php-ext-enable pdo_mysql xdebug \
	&& docker-php-ext-configure intl 


RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \ 
	&& php composer-setup.php \ 
	&& php -r "unlink('composer-setup.php');" \ 
	&& mv composer.phar /usr/local/bin/composer

RUN echo $(date + "%Y-%m-%d %H-%M-%S") Generation du fichier de log >> /var/log/xdebug.log

WORKDIR /app
COPY . .

RUN export COMPOSER_ALLOW_SUPERUSER=1 \
	&& composer install