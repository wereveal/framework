#!/bin/bash
useJqueryUi="no"
useLibPackageJson="no"
useGit="no"
while getopts ":u:l:g" opt; do
    case $opt in
        u)
            useJqueryUi="yes"
            ;;
        l)
            useLibPackageJson="yes"
            ;;
        g)
            useGit="yes"
            ;;
        \?)
            echo "Valid options are -u (JqueryUI) -f (FAPro) -g (update git repos)" >&2
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

if [ "$useGit" = "yes" ]; then
    echo "Updating this site"
    git pull
    echo ""
    echo "Updating the Library."
    cd src/apps/Ritc/Library
    git pull
    cd ../../../../
    echo ""
fi

if [ ! -f composer.json ]
then
    echo "The composer.json file must exist at the base of the site."
else
    echo "Updating composer installed files."
    echo ""
    if [ -x $(command -v composer.phar) ]; then
        composer.phar update
    else
        composer update
    fi
fi

echo "Installing public/assets/vendor files"
if [ "$useLibPackageJson" = "yes" ]; then
    cp src/apps/Ritc/Library/resources/config/package.json.txt public/assets/package.json
    cp src/apps/Ritc/Library/resources/config/npmrc.txt public/assets/.npmrc
fi
echo "Yarn stuff"
bash src/bin/doYarn.sh

if [ "$useJqueryUi" = "yes" ]; then
    echo "JqueryUi stuff"
    bash src/bin/doJqueryUi.sh
fi
echo "Uglifying JS"
bash src/bin/doUglifyJS.sh
echo "Doing Sass stuff"
bash src/bin/doSass.sh
echo ""

