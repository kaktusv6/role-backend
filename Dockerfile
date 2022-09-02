ARG PHP_EXTS="bcmath ctype fileinfo mbstring pdo pgsql pdo_pgsql dom pcntl"
ARG PHP_PECL_EXTS="xdebug"

FROM php:8.1-alpine as app-base

RUN set -eux ; \
  apk add --no-cache --virtual .composer-rundeps \
    bash \
    coreutils \
    git \
    make \
    openssh-client \
    patch \
    subversion \
    tini \
    unzip \
    zip \
    $([ "$(apk --print-arch)" != "x86" ] && echo mercurial) \
    $([ "$(apk --print-arch)" != "armhf" ] && echo p7zip)

RUN printf "# composer php cli ini settings\n\
date.timezone=UTC\n\
memory_limit=-1\n\
" > $PHP_INI_DIR/php-cli.ini

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /tmp
ENV COMPOSER_VERSION 2.4.1

RUN set -eux ; \
  # install https://github.com/mlocati/docker-php-extension-installer
  curl \
    --silent \
    --fail \
    --location \
    --retry 3 \
    --output /usr/local/bin/install-php-extensions \
    --url https://github.com/mlocati/docker-php-extension-installer/releases/download/1.2.58/install-php-extensions \
  ; \
  echo 182011b3dca5544a70fdeb587af44ed1760aa9a2ed37d787d0f280a99f92b008e638c37762360cd85583830a097665547849cb2293c4a0ee32c2a36ef7a349e2 /usr/local/bin/install-php-extensions | sha512sum --strict --check ; \
  chmod +x /usr/local/bin/install-php-extensions ; \
  # install necessary/useful extensions not included in base image
  install-php-extensions \
    bz2 \
    zip \
  ; \
  # install public keys for snapshot and tag validation, see https://composer.github.io/pubkeys.html
  curl \
    --silent \
    --fail \
    --location \
    --retry 3 \
    --output /tmp/keys.dev.pub \
    --url https://raw.githubusercontent.com/composer/composer.github.io/e7f28b7200249f8e5bc912b42837d4598c74153a/snapshots.pub \
  ; \
  echo 572b963c4b7512a7de3c71a788772440b1996d918b1d2b5354bf8ba2bb057fadec6f7ac4852f2f8a8c01ab94c18141ce0422aec3619354b057216e0597db5ac2 /tmp/keys.dev.pub | sha512sum --strict --check ; \
  curl \
    --silent \
    --fail \
    --location \
    --retry 3 \
    --output /tmp/keys.tags.pub \
    --url https://raw.githubusercontent.com/composer/composer.github.io/e7f28b7200249f8e5bc912b42837d4598c74153a/releases.pub \
  ; \
  echo 47f374b8840dcb0aa7b2327f13d24ab5f6ae9e58aa630af0d62b3d0ea114f4a315c5d97b21dcad3c7ffe2f0a95db2edec267adaba3f4f5a262abebe39aed3a28 /tmp/keys.tags.pub | sha512sum --strict --check ; \
  # download installer.php, see https://getcomposer.org/download/
  curl \
    --silent \
    --fail \
    --location \
    --retry 3 \
    --output /tmp/installer.php \
    --url https://raw.githubusercontent.com/composer/getcomposer.org/f24b8f860b95b52167f91bbd3e3a7bcafe043038/web/installer \
  ; \
  echo 3137ad86bd990524ba1dedc2038309dfa6b63790d3ca52c28afea65dcc2eaead16fb33e9a72fd2a7a8240afaf26e065939a2d472f3b0eeaa575d1e8648f9bf19 /tmp/installer.php | sha512sum --strict --check ; \
  # install composer phar binary
  php /tmp/installer.php \
    --no-ansi \
    --install-dir=/usr/bin \
    --filename=composer \
    --version=${COMPOSER_VERSION} \
  ; \
  composer --ansi --version --no-interaction ; \
  composer diagnose ; \
  rm -f /tmp/installer.php ; \
  find /tmp -type d -exec chmod -v 1777 {} +

ARG PHP_EXTS

RUN mkdir -p /var/www /var/www/bin

WORKDIR /var/www

RUN apk update && apk add --no-cache libzip-dev zip libpq-dev postgresql-dev

RUN addgroup -S composer \
    && adduser -S composer -G composer \
    && chown -R composer /var/www

RUN apk add --virtual build-dependencies --no-cache ${PHPIZE_DEPS} openssl ca-certificates libpq-dev libxml2-dev oniguruma-dev && \
    docker-php-ext-install -j$(nproc) ${PHP_EXTS}

RUN apk del build-dependencies

USER composer

COPY --chown=composer composer.json composer.lock ./

RUN composer install --no-scripts --no-autoloader --prefer-dist

COPY --chown=composer . .

RUN composer install --prefer-dist


FROM php:8.1-fpm-alpine as app-backend

ARG PHP_EXTS
ARG APP_ENV="production"

WORKDIR /var/www

RUN apk update && apk add  --no-cache libzip-dev zip libpq-dev postgresql-dev

RUN apk add --virtual build-dependencies --no-cache ${PHPIZE_DEPS} openssl ca-certificates libpq-dev libxml2-dev oniguruma-dev && \
    docker-php-ext-install -j$(nproc) ${PHP_EXTS}

RUN if [[ "$APP_ENV" = "local" ]] ; then pecl install xdebug ; fi
RUN if [[ "$APP_ENV" = "local" ]] ; then docker-php-ext-enable xdebug ; fi

RUN apk del build-dependencies

RUN if [[ "$APP_ENV" = "local" ]] ; then echo http://dl-2.alpinelinux.org/alpine/edge/community/ >> /etc/apk/repositories ; fi
RUN if [[ "$APP_ENV" = "local" ]] ; then apk add --no-cache shadow ; fi

COPY --from=app-base --chown=www-data /var/www /var/www

RUN if [[ "$APP_ENV" = "local" ]] ; then mv ./docker/app/confs/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini ; fi

RUN chmod +x ./docker/app/scripts/deploy.sh

CMD ./docker/app/scripts/deploy.sh


FROM php:8.1-alpine as base-cli

ARG PHP_EXTS

WORKDIR /var/www

RUN apk update && apk add  --no-cache libzip-dev zip libpq-dev postgresql-dev

RUN apk add --virtual build-dependencies --no-cache ${PHPIZE_DEPS} openssl ca-certificates libpq-dev libxml2-dev oniguruma-dev && \
    docker-php-ext-install -j$(nproc) ${PHP_EXTS}
RUN apk del build-dependencies

COPY --from=app-base --chown=www-data /var/www /var/www


#FROM base-cli as app-migrator
#
#RUN chmod +x docker/app/migrator.sh
#
#CMD ./docker/app/scripts/migrator.sh


FROM nginx:1.21-alpine as web

WORKDIR /var/www

COPY docker/web/confs/default.conf.template /etc/nginx/templates/default.conf.template

COPY --from=app-base /var/www/public/index.php /var/www/public/index.php
