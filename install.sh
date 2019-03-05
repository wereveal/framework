#!/bin/bash
useJqueryUi="no"
useFAPro="no"
while getopts ":u:f" opt; do
    case $opt in
        u)
            useJqueryUi="yes"
            ;;
        f)
            useFAPro="yes"
            ;;
        \?)
            echo "Valid options are -u" >&2
            ;;
    esac
done

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
    if [ ! -d src/apps/Ritc/Library ]; then
        echo "Installing the Library."
        git clone ritc:/srv/git/ritc/library src/apps/Ritc/Library
    else
        echo "Updating the Library."
        cd src/apps/Ritc/Library
        git pull
        cd ../../../../
    fi

    if [ ! -d ./vendor ]
    then
        if [ ! -f composer.json ]; then
            echo "The composer.json file must exist at the base of the site."
            exit 1
        else
            if [ -x $(command -v composer.phar) ]; then
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
            if [ -x $(command -v composer.phar) ]; then
                composer.phar update
            else
                composer update
            fi
        fi
    fi

    echo "Running the php install script"
    php src/bin/install.php

    echo "Installing public/assets/vendor files"
    echo $useFAPro
    if [ "$useFAPro" = "yes" ]; then
        cp src/apps/Ritc/Library/resources/config/package.json.txt public/assets/package.json
        cp src/apps/Ritc/Library/resources/config/npmrc.txt public/assets/.npmrc
    else
        cp src/config/install_files/package.json.txt public/assets/package.json
        if [ -f public/assets/.npmrc ]; then
            rm public/assets/.npmrc
        fi
    fi
    bash src/bin/doYarn.sh

    if [ "$useJqueryUi" = "yes" ]; then
        echo "Installing jQueryUi"
        bash src/bin/doJqueryUi.sh
    fi

    echo "Running Sass"
    bash src/bin/doSass.sh

    echo "Running uglifyJs"
    bash src/bin/doUglifyJS.sh

else
    echo "The src/config/install_config.php file must be created and configured first.\nSee src/config/install_files/install_config.commented.txt for full details."
    cp src/config/install_files/install_config.php.txt src/config/install_config.php
    vi src/config/install_config.php
fi
