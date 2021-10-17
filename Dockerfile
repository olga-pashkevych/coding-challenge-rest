FROM php:8.0.2-fpm-buster

RUN apt-get update && apt-get install -y git libicu-dev \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-install intl

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

RUN curl https://getcomposer.org/installer > composer-setup.php && php composer-setup.php &&\
    mv composer.phar /usr/local/bin/composer && rm composer-setup.php

COPY ./ /usr/src/app

WORKDIR /usr/src/app/

RUN /usr/local/bin/composer install --ignore-platform-reqs --no-scripts

CMD ["symfony", "server:start"]
