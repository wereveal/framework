#!/bin/bash
if [ ! -x $(command -v composer) ]; then
    if [ ! -x $("command -v composer.phar") ]; then
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
git pull
if [ ! -f composer.json ]
then
    echo "The composer.json file must exist at the base of the site."
else
    if [ -x $(command -v composer.phar) ]; then
        composer.phar update
    else
        composer update
    fi
fi

bash src/bin/doYarn.sh
bash src/bin/doJqueryUi.sh
bash src/bin/doUglifyJS.sh
bash src/bin/doSass.sh

echo "Updating the Library."
cd src/apps/Ritc/Library
git pull
cd ../../../../
