#!/bin/sh

#pulling changes
echo "Pulling changes"
git fetch --all
git reset --hard origin/master
echo "Install new composer packages"
composer install
echo "Clearing cache"
php bin/console cache:clear
echo "Apply new migrations"
php bin/console doctrine:mig:mig

echo "Compiling assets"
yarn install
yarn encore production
