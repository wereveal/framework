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

if [ -d src/apps/Ritc/Library/resources/assets/scss/ ]; then
  theDir='src/apps/Ritc/Library/resources/assets/scss/'
  theOtherDir='public/assets/css/'
else
  if [ -d ../apps/Ritc/Library/resources/assets/scss/ ]; then
    theDir='../apps/Ritc/Library/resources/assets/scss/'
    theOtherDir='../../public/assets/css/'
  else
    theDir=''
    theOtherDir=''
  fi
fi
if [[ ${theDir} != "" ]]; then
  sass --update -s compressed ${theDir}:${theOtherDir}
fi