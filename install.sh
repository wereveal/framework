#!/bin/bash
useJqueryUi="no"
useLibPackageJson="no"
useYarn="no"
while getopts ":u:l:y" opt; do
    case $opt in
        u)
            useJqueryUi="yes"
            ;;
        l)
            useLibPackageJson="yes"
            ;;
        y)
            useYarn="yes"
            ;;
        \?)
            echo "Valid options are -u (jQueryUI) -l (Library package.json)" >&2
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
    echo "npm and yarn must be installed"
    exit 1
fi
if [ "$useYarn" = "yes" ]; then
  if [ ! -x "$(command -v yarn)" ]; then
    echo "yarn must be installed"
    exit 1
  fi
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

    echo "Running the php install script"
    php src/bin/install.php

    echo "Installing public/assets/vendor files"
    echo "Using the package.json file from Library: " $useLibPackageJson
    # LibPackageJson installs additional node packages
    if [ "$useLibPackageJson" = "yes" ]; then
        cp src/apps/Ritc/Library/resources/config/package.json.txt public/assets/package.json
        cp src/apps/Ritc/Library/resources/config/npmrc.txt public/assets/.npmrc
    else
        cp src/config/install_files/package.json.txt public/assets/package.json
        if [ -f public/assets/.npmrc ]; then
            rm public/assets/.npmrc
        fi
    fi
    # bash src/bin/doYarn.sh
    bash src/bin/doNpm.sh

    if [ "$useJqueryUi" = "yes" ]; then
        echo "Installing jQueryUi"
        bash src/bin/doJqueryUi.sh
    fi

    echo "Running Sass"
    bash src/bin/doSass.sh

    echo "Running uglifyJs"
    bash src/bin/doUglifyJS.sh
    mv src/config/install_config.php private/
else
    echo "The src/config/install_config.php file must be created and configured first."
    echo "See src/config/install_files/install_config.commented.txt for full details."
    echo "Do you wish to create the file and configure it now? (y, n)"
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
