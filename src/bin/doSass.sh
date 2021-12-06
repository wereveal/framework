#!/bin/bash
if [ -d public/assets/css ]; then
  thePublicDir='public/assets/css/'
elif [ -d ../../public/assets/css ]; then
  thePublicDir='../../public/assets/css/'
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
sass --update --style=compressed ${theDir}:${thePublicDir}

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
      sass --update --style=compressed "$theScssDir":"$thePublicDir"
    fi
  done
done
