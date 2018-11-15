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
sass --update -s compressed ${theDir}:${thePublicDir}

if [ -d src/apps/ ]; then
 appsDir='src/apps'
elif [ -d ../apps/ ]; then
 appsDir='../apps'
fi

for dir in $(ls $appsDir)
do
  for inner_dir in $(ls $appsDir/$dir/)
  do
    theScssDir=$appsDir/$dir/$inner_dir/resources/assets/scss
    if [ -d $theScssDir ]; then
      sass --update -s compressed ${theScssDir}:${thePublicDir}
    fi
  done
done
