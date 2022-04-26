#!/bin/bash
theDir=''
if [ ! -f "$theDir"/package.json ]; then
    if [ -f ../config/install_files/package.json.txt ]; then
        cp ../config/install_files/package.json.txt "$theDir"/
    fi
fi
cd "$theDir" || exit
npm install
