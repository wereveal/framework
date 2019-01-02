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
echo "Updating this site"
git pull

echo "Updating the Library."
cd src/apps/Ritc/Library
git pull
cd ../../../../

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

echo "Installing public/assets/vendor files"
if [ "$useFAPro" = "yes" ]; then
    cp src/apps/Ritc/Library/resources/config/package.json.txt public/assets/package.json
    cp src/apps/Ritc/Library/resources/config/npmrc.txt public/assets/.npmrc
fi
bash src/bin/doYarn.sh

if [ "$useJqueryUi" = "yes" ]; then
    bash src/bin/doJqueryUi.sh
fi
bash src/bin/doUglifyJS.sh
bash src/bin/doSass.sh

