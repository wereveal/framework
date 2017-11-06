#!/bin/bash
if [ ! -f composer.json ]
then
    echo "The composer.json file must exist at the base of the site."
else
    if [ -x composer.phar ]
    then
        composer.phar update
    else
        composer update
    fi
fi

if [ -f public/assets/package.json ]
then
    cd public/assets
    yarn install --modules-folder vendor
    sass --update scss:css
    cd ../../
else
    echo "The package.json file must exist in the public/assets directory."
fi

echo "Updating the Library."
cd src/apps/Ritc/Library
git pull
cd ../../../../
