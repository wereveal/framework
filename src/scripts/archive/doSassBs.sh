#!/bin/bash
if [ -d public/assets/css ]; then
  thePublicDir='public/assets/css/'
  nmDir='public/assets/node_modules'
elif [ -d ../public/assets/css ]; then
  thePublicDir='../public/assets/css'
  nmDir='../public/assets/node_modules'
elif [ -d ../../public/assets/css ]; then
  thePublicDir='../../public/assets/css/'
  nmDir='../../public/assets/node_modules'
else
  exit 1
fi

if [ -d src/scss/ ]; then
  theDir='src/scss/'
elif [ -d ../scss/ ]; then
  theDir='../scss/'
else
  exit 1
fi
cp "$theDir"/styles_bootstrap.scss.txt "$theDir"/styles.scss
sass --load-path="$nmDir"/bootstrap/scss --update --style=compressed ${theDir}:${thePublicDir}

if [ -d src/apps/ ]; then
 appsDir='src/apps'
elif [ -d ../apps/ ]; then
 appsDir='../apps'
fi

for theAppNamespace in "$appsDir"/*
do
  for theApp in "$appsDir"/"$theAppNamespace"/*
  do
    theScssDir=$appsDir/$theAppNamespace/$theApp/resources/assets/scss
    if [ -d "$theScssDir" ]; then
      sass --load-path="$nmDir"/bootstrap/scss --update --style=compressed "$theScssDir":"$thePublicDir"
    fi
  done
done
