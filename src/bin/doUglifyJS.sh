#!/usr/bin/env bash
if [ -d public/assets/js/ ]; then
  thePublicDir='public/assets/js/'
elif [ -d ../../public/assets/js/ ]; then
  thePublicDir='../../public/assets/js/'
else
  exit 1
fi

if [ -d src/apps/ ]; then
 appsDir='src/apps'
elif [ -d ../apps/ ]; then
 appsDir='../apps'
fi

for dir in "$appsDir"/*
do
  for inner_dir in "$appsDir"/"$dir"/*
  do
    theJsDir=$appsDir/$dir/$inner_dir/resources/assets/js
    if [ -d "$theJsDir" ]; then
      for thing in "$theJsDir"/*.js
      do
        shortThing=$(basename "$thing")
        uglifyjs --compress --mangle --source-map --output "$thePublicDir"/"$shortThing" "$thing"
      done
    fi
  done
done
