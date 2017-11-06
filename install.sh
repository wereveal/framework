#!/bin/bash
if [ -f src/config/install_config.php ];
then
    if [ ! -d ./vendor ]
    then
        if [ ! -f composer.json ]
        then
            echo "The composer.json file must exist at the base of the site."
        else
            if [ -x composer.phar ]
            then
                composer.phar install
            else
                composer install
            fi
        fi
    else
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
    fi

    if [ ! -d public/assets/vendor ]
    then
        if [ -f public/assets/package.json ]
        then
            cd public/assets
            yarn install --modules-folder vendor
            yarn config set modules-folder vendor
            sass --update scss:css
            cd ../../
        else
            echo "The package.json file must exist in the public/assets directory."
        fi
    else
        if [ -f public/assets/package.json ]
        then
            cd public/assets
            yarn install --modules-folder vendor
            yarn config set modules-folder vendor
            sass --update scss:css
            cd ../../
        else
            echo "The package.json file must exist in the public/assets directory."
        fi
    fi

    if [ ! -d src/apps/Ritc/Library ]
    then
        echo "Installing the Library."
        git clone ritc:/srv/git/ritc/library src/apps/Ritc/Library
    else
        echo "Updating the Library."
        cd src/apps/Ritc/Library
        git pull
        cd ../../../../
    fi
    php src/bin/install.php
else
    echo "The src/config/install_config.php file must be created and configured first.\nSee src/config/install_files/install_config.php.txt"
fi
