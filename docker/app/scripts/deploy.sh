#!/bin/sh

# Check .env
if [ -f .env ]; then
  APP_ENV="local"
fi

ln -s /vault/secrets/env /var/www/.env

# Run script for localhost
if [ "$APP_ENV" = "local" ]; then

  # Fix permissions for Linux users
  SRC_DIR=/var/www

  USER=www-data
  GROUP=www-data

  uid=$(stat -c '%u' $SRC_DIR)
  gid=$(stat -c '%g' $SRC_DIR)

  echo $uid > /root/uid
  echo $gid > /root/gid

  usermod -u $uid $USER
  groupmod -g $gid $GROUP

  chown -R $USER:$GROUP $SRC_DIR

  php artisan migrate
  php artisan db:seed
fi

exec php-fpm
