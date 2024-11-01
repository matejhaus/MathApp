FROM php:8.3-cli AS build

RUN apt-get update && apt-get install -y zip unzip nodejs npm

# === Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

# === Copy the project and install dependencies
COPY . /opt/app
WORKDIR /opt/app

# Just for build
ENV APP_SECRET=SG8+KOgvZ8r+FNt+33K0dtfqD9jphVOWnj/ajbeRT+8=
ENV DATABASE_URL=sqlite:///%kernel.project_dir%/var/data.db

RUN touch .env
RUN echo "APP_ENV=prod" >> .env
RUN echo "MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0" >> .env

RUN composer instal --no-dev --classmap-authoritative
RUN npm install
RUN npm run build

RUN rm -rf node_modules

# === App image
FROM docker.io/unit:1.33.0-php8.3 AS deploy

# php extensions used during runtime
RUN apt-get update && apt-get install -y libxml2-dev
RUN docker-php-ext-install opcache pdo pdo_mysql simplexml dom

# webserver configuration
COPY docker/unit-config.json /docker-entrypoint.d/config.json

# built app
COPY --from=build /opt/app /opt/app
WORKDIR /opt/app

RUN chown -R unit:unit ./var

