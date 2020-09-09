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
cd $theDir
npm install
if [ ! -d vendor ]; then
  ln -s node_modules vendor
fi