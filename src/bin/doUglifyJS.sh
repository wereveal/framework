#!/usr/bin/env bash
if [ -d src/js/ ]; then
  theDir='src/js/'
  theOtherDir='public/assets/js/'
else
  if [ -d ../js/ ]; then
    theDir='../js/'
    theOtherDir='../../public/assets/js/'
  else
    theDir=''
    theOtherDir='min/'
  fi
fi

for thing in $(ls ${theDir}*.js)
do
    shortThing=$(basename ${thing})
    uglifyjs ${thing} --compress --mangle --output ${theOtherDir}${shortThing}
done