#!/bin/bash
useBootstrap="y"
useBulma="n"
whereIam=$(pwd)
today=`date +%Y-%m-%d`
while getopts ":b:d:n" opt; do
    case $opt in
        d)
            useBootstrap="y"
            useBulma="n"
            ;;
        b)
            useBulma="y"
            useBootstrap="n"
            ;;
        n)
            useBulma="n"
            useBootstrap="n"
            ;;
        \?)
            echo "Valid options are -d (use Bootstrap - the default), -b (use Bulma), or -n for neither Bootstrap or Bulma" >&2
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
            else
                composer install
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
    php src/scripts/install.php

    echo "getting public assets in place"
    echo "First npm install"
    bash src/scripts/doNpm.sh
    echo "Next Running Sass"
    if [ "$useBootstrap" = "y" ]; then
      bash src/scripts/doSass.sh -d
    fi
    if [ "$useBulma" = "y" ]; then
      bash src/scripts/doSass.sh -b
    fi
    if [ "$useBulma" = "n" ] && [ "$useBootstrap" = "n" ]; then
      bash src/scripts/doSass.sh -n
    fi
    echo "Finally Running uglifyJs"
    bash src/scripts/doUglifyJS.sh
    mv src/config/install_config.php private/install_config."$today".php
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
