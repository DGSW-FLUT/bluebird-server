#!/bin/sh

# update source code
git pull origin master --force

# generate swagger assets
php artisan swagger-lume:publish
php artisan swagger-lume:generate

git reset --hard
