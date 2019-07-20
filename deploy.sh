#!/bin/sh

# update source code
git pull origin master --force

composer update

# generate swagger assets
php artisan swagger-lume:publish
php artisan swagger-lume:generate

# update composer
