#!/bin/bash
if [ -d public/assets/vendor/jquery-ui/ ]; then
  theDir='public/assets/vendor/jquery-ui/'
  theOtherDir='../../../../'
else
  if [ -d ../../public/assets/vendor/jquery-ui/ ]; then
    theDir='../../public/assets/vendor/jquery-ui/'
    theOtherDir='../../../../src/bin/'
  else
    theDir='vendor/jquery-ui/'
    theOtherDir='../../.'
  fi
fi
cd ${theDir} || exit
npm install && npm test && grunt
cd ${theOtherDir} || exit