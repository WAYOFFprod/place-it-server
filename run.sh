#!/bin/sh

alias php='/opt/php82/bin/php'
echo "alias php='/opt/php82/bin/php'" >> /.bash_profile

export PATH=/opt/php82/bin:$PATH
echo "export PATH=/opt/php82/bin:\$PATH" >> /.bash_profile

# Put the application into maintenance
(php artisan down) || true

mkdir -p /var/www/html/storage/app/canvas

cd server.place-it.wayoff.tv/

composer install

# migrate db if needed
php artisan migrate --force

# clear cache and config
php artisan config:clearp
php artisan cache:clear
php artisan view:clear

# php artisan queue:restart
# php artisan sitemap:generate

# Bring the application out of maintenance mode
php artisan up

