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
if [ -f ${theDir}package.json ]; then
    yarn config set modules-folder vendor --cwd ${theDir}
    yarn install --cwd ${theDir} --modules-folder ${theDir}vendor
else
    echo "The package.json file is missing"
    exit 1
fi