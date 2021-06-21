#!/bin/bash

cp .env.development .env

# Install/update composer dependecies
echo -e "\n----------"
echo "Install/update composer dependecies"
./composer.phar install --no-interaction --prefer-dist --optimize-autoloader --no-dev
./composer.phar dump-autoload
