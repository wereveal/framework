#!/bin/bash
useBootstrap="n"
whereIam=$(pwd)
while getopts ":b" opt; do
    case $opt in
        b)
            useBootstrap="y"
            ;;
        \?)
            echo "Valid options are -b (use Bootstrap instead of Bulma)" >&2
            ;;
    esac
done

if [ ! -x "$(command -v composer)" ]; then
    if [ ! -x "$(command -v composer.phar)" ]; then
        echo "composer must be installed"
        exit 1
    fi
fi
if [ ! -x "$(command -v npm)" ]; then
    echo "npm must be installed"
    exit 1
fi
if [ ! -x "$(command -v git)" ]; then
    echo "git must be installed"
    exit 1
fi
if [ ! -x "$(command -v php)" ]; then
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
            if [ -x "$(command -v composer.phar)" ]; then
                composer.phar install
                if [ "$useBootstrap" = "yes" ]; then
                  composer.phar install bootstrap
                fi
            else
                composer install
                if [ "$useBootstrap" = "yes" ]; then
                  composer install bootstrap
                fi
            fi
        fi
    else
        if [ ! -f composer.json ]; then
            echo "The composer.json file must exist at the base of the site."
            exit 1
        else
            if [ -x "$(command -v composer.phar)" ]; then
                composer.phar update
            else
                composer update
            fi
        fi
    fi

    ### Must install the Library First
    if [ ! -d src/apps/Ritc/Library ]; then
      echo "Installing the Library"
      git clone ritc:/srv/git/ritc/library src/apps/Ritc/Library
    else
      echo "Updating the Library"
      cd src/apps/Ritc/Library || exit
      git pull
      cd "$whereIam" || exit
    fi
    echo "Running the php install script"
    php src/bin/install.php

    echo "Updating the public/assets"
    echo "First npm install"
    bash src/bin/doNpm.sh
    echo "Next Running Sass"
    if [ "$useBootstrap" = "y" ]; then
      bash src/bin/doSassBs.sh
    else
      bash src/bin/doSass.sh
    fi
    echo "Finally Running uglifyJs"
    bash src/bin/doUglifyJS.sh
    mv src/config/install_config.php private/
else
    echo "The src/config/install_config.php file must be created and configured first."
    echo "See src/config/install_files/install_config.commented.txt for full details."
    echo "Do you wish to create the file and configure it now? (y, n)"
    # shellcheck disable=SC2162
    read theAnswer
    if [ "$theAnswer" = "y" ]; then
        echo "The file will be created in the config directory."
        cp src/config/install_files/install_config.php.txt src/config/install_config.php
        vi src/config/install_config.php
        echo "Now re-run install.sh"
    else
        echo "Be sure to create the file and update its configuration then re-run this script (install.sh)."
    fi
fi
