#!/bin/bash
useBulma="n"
useBootstrap="y"
rootDir=$(pwd)
while getopts ":b:d:n" opt; do
    case $opt in
        b)
            useBulma="y"
            useBootstrap="n"
            ;;
        d)
          useBulma="n"
          useBootstrap="y"
          ;;
        n)
          useBulma="n"
          useBootstrap="n"
          ;;
        \?)
            echo "Valid options are -b for Bulma, -d Bootstrap (default), -n doesn't use either" >&2
            ;;
    esac
done
if [ -d "$rootDir"/public/assets/css ]; then
  thePublicDir=$rootDir'/public/assets/css/'
  nmDir='node_modules'
elif [ -d ../public/assets/css ]; then
  thePublicDir='../public/assets/css'
  nmDir='../node_modules'
elif [ -d ../../public/assets/css ]; then
  thePublicDir='../../public/assets/css/'
  nmDir='../../node_modules'
else
  exit 1
fi
# echo "Public Dir -- "$thePublicDir;
# echo "";

if [ -d "$rootDir"/src/scss/ ]; then
  theDir=$rootDir'/src/scss/'
elif [ -d ../scss/ ]; then
  theDir='../scss/'
else
  exit 1
fi
# echo "theDir -- "$theDir;
# echo "";

if [ "$useBulma" = "y" ]; then
  sass --load-path="$nmDir"/bulma --update --style=compressed ${theDir}:${thePublicDir}
fi
if [ "$useBootstrap" = "y" ]; then
  sass --load-path="$nmDir"/bootstrap --update --style=compressed ${theDir}:${thePublicDir}
fi
if [ "$useBootstrap" = "n" ] && [ "$useBootstrap" = "n" ]; then
  sass --update --style=compressed ${theDir}:${thePublicDir}
fi

if [ -d "$rootDir"/src/apps/ ]; then
 appsDir=$rootDir"/src/apps";
elif [ -d ../apps/ ]; then
 appsDir="../apps";
fi
# echo $appsDir
# echo '';
for theAppNamespace in "$appsDir"/*
do
  for theApp in "$theAppNamespace"/*
  do
#    echo "$theApp"
#    echo '';
    if [ -d "$theApp" ]; then
      theScssDir=$theApp/resources/assets/scss
#      echo "$theScssDir";
#      echo "---";
      if [ -d "$theScssDir" ]; then
        if [ "$useBulma" = "y" ]; then
          sass --load-path="$nmDir"/bulma --update --style=compressed "$theScssDir":"$thePublicDir"
        fi
        if [ "$useBootstrap" = "y" ]; then
#          echo "$theScssDir" '----' "$thePublicDir"
#          echo '-'
          sass --load-path="$nmDir"/bootstrap --update --style=compressed "$theScssDir":"$thePublicDir"
        fi
        if [ "$useBootstrap" = "n" ] && [ "$useBootstrap" = "n" ]; then
          sass --update --style=compressed  "$theScssDir":"$thePublicDir"
        fi
      fi
    fi
  done
done
