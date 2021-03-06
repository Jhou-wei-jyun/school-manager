#!/bin/bash

# fix key if needed
if [ -z "$APP_KEY" ]
then
  echo "Please re-run this container with an environment variable \$APP_KEY"
  echo "An example APP_KEY you could use is: "
  /var/www/html/artisan key:generate --show
  exit
fi

if [ -f /var/lib/storage/ssl/system-ssl.crt -a -f /var/lib/storage/ssl/system-ssl.key ]
then
  a2enmod ssl
else
  a2dismod ssl
fi

# create data directories
for dir in \
  'data/private_uploads' \
  'data/uploads/accessories' \
  'data/uploads/avatars' \
  'data/uploads/barcodes' \
  'data/uploads/categories' \
  'data/uploads/companies' \
  'data/uploads/components' \
  'data/uploads/consumables' \
  'data/uploads/departments' \
  'data/uploads/locations' \
  'data/uploads/manufacturers' \
  'data/uploads/models' \
  'data/uploads/suppliers' \
  'dumps' \
  'keys'
do
  [ ! -d "/var/lib/storage/$dir" ] && mkdir -p "/var/lib/storage/$dir"
done

chown -R docker:root /var/lib/storage/data/*
chown -R docker:root /var/lib/storage/dumps
chown -R docker:root /var/lib/storage/keys

# Fix php settings
if [ -v "PHP_UPLOAD_LIMIT" ]
then
    echo "Changing upload limit to ${PHP_UPLOAD_LIMIT}"
    sed -i "s/^upload_max_filesize.*/upload_max_filesize = ${PHP_UPLOAD_LIMIT}M/" /etc/php/*/apache2/php.ini
fi


# If the Oauth DB files are not present copy the vendor files over to the db migrations
#if [ ! -f "/var/www/html/database/migrations/*create_oauth*" ]
#then
#  cp -ax /var/www/html/vendor/laravel/passport/database/migrations/* /var/www/html/database/migrations/
#fi

echo "Launch cron service..."
service cron start || true
echo "Launch supervisord service..."
exec supervisord -c /supervisord.conf
