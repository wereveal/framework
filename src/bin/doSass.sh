#!/bin/bash
if [ -d src/scss/ ]; then
  theDir='src/scss/'
  theOtherDir='public/assets/css/'
else
  if [ -d ../scss/ ]; then
    theDir='../scss/'
    theOtherDir='../../public/assets/css/'
  else
    theDir='scss'
    theOtherDir='css'
  fi
fi
sass --update -s compressed ${theDir}:${theOtherDir}
