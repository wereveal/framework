#!/bin/bash
if [ -d public/assets/ ]; then
  theDir='public/assets/'
else
  if [ -d ../../public/assets/ ]; then
    theDir='../../public/assets/'
  else
    theDir=''
  fi
fi
if [ ! -f $theDir/package.json ]; then
    if [ -f ../config/install_files/package.json.bulma.txt ]; then
        cp ../config/install_files/package.json.bulma.txt $theDir/
    fi
fi
cd $theDir
npm install
if [ ! -d vendor ]; then
  ln -s node_modules vendor
fi
