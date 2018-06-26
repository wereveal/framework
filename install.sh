#!/bin/bash
if [ ! -x $(command -v composer) ]; then
    if [ ! -x $(command -v composer.phar) ]; then
        echo "composer must be installed"
        exit 1
    fi
fi
if [ ! -x $(command -v npm) ]; then
    echo "npm and yarn must be installed"
    exit 1
fi
if [ ! -x $(command -v yarn) ]; then
    echo "yarn must be installed"
    exit 1
fi
if [ ! -x $(command -v git) ]; then
    echo "git must be installed"
    exit 1
fi
if [ ! -x $(command -v php) ]; then
    echo "php must be installed"
    exit 1
fi
if [ -f src/config/install_config.php ]; then
    if [ ! -d ./vendor ]
    then
        if [ ! -f composer.json ]; then
            echo "The composer.json file must exist at the base of the site."
            exit 1
        else
            if [ -x composer.phar ]; then
                composer.phar install
            else
                composer install
            fi
        fi
    else
        if [ ! -f composer.json ]; then
            echo "The composer.json file must exist at the base of the site."
            exit 1
        else
            if [ -x composer.phar ]; then
                composer.phar update
            else
                composer update
            fi
        fi
    fi

    bash src/bin/doYarn.sh
    bash src/bin/doJqueryUi.sh
    bash src/bin/doSass.sh
    bash src/bin/doUglifyJS.sh

    if [ ! -d src/apps/Ritc/Library ]; then
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
