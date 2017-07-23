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

if [ -f public/assets/bower.json ]
then
    cd public/assets
    bower update
    cd ../../
else
    echo "The bower.json file must exist in the public/assets directory."
fi

echo "Updating the Library."
cd src/apps/Ritc/Library
git pull
cd ../../../../
