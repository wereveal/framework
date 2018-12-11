#!/usr/bin/env bash
if [ -d public/assets/js/ ]; then
  thePublicDir='public/assets/js/'
  theSrcDir='src/js/'
elif [ -d ../../public/assets/js/ ]; then
  thePublicDir='../../public/assets/js/'
  theSrcDir='../js/'
else
  exit 1
fi

for thing in $(ls ${theSrcDir}*.js)
do
    shortThing=$(basename ${thing})
    uglifyjs ${thing} --compress --mangle --source-map --output ${thePublicDir}${shortThing}
done


if [ -d src/apps/ ]; then
 appsDir='src/apps'
elif [ -d ../apps/ ]; then
 appsDir='../apps'
fi

for dir in $(ls $appsDir)
do
  for inner_dir in $(ls $appsDir/$dir/)
  do
    theJsDir=$appsDir/$dir/$inner_dir/resources/assets/js
    if [ -f $theJsDir/*.js ]; then
      for thing in $(ls $theJsDir/*.js)
      do
        shortThing=$(basename ${thing})
        uglifyjs $thing --compress --mangle --source-map --output $thePublicDir/$shortThing
      done
    fi
  done
done
