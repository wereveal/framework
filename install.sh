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
        echo "."
    fi

    if [ ! -d public/assets/vendor ]
    then
        if [ -f public/assets/bower.json ]
        then
            cd public/assets
            bower install
            cd ../../
        else
            echo "The bower.json file must exist in the public/assets directory."
        fi
    else
        echo "."
    fi

    if [ ! -d src/apps/Ritc/Library ]
    then
        git clone ritc:/srv/git/ritc/library src/apps/Ritc/Library
    else
        echo "."
    fi
    php src/bin/install.php
else
    echo "The src/config/install_config.php file must be created and configured first.\nSee src/config/install_files/install_config.php.txt"
fi
