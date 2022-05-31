#!/bin/bash
theDir='./';
thePublicDir='./public/assets/vendor';
if [ ! -d "$thePublicDir" ]; then
  if [ -d '../../public/assets/vendor' ]; then
    thePublicDir='../../public/assets/vendor'
  else
    thePublicDir='./'
  fi
fi
useBulma="n";
useBootstrap="y";
while getopts ":b:d:n" opt; do
    case $opt in
        b)
            useBulma="y"
            useBootstrap="n"
            ;;
        d)
          useBulma="n"
          useBootstrap="y"
          ;;
        n)
          useBulma="n"
          useBootstrap="n"
          ;;
        \?)
            echo "Valid options are -b for Bulma, -d Bootstrap (default), -n doesn't use either" >&2
            ;;
    esac
done
if [ ! -f "$theDir"package.json ]; then
  if [ "$useBulma" = "Y" ]; then
    if [ -f ../config/install_files/package_bulma.json.txt ]; then
        cp ../config/install_files/package_bulma.json.txt "$theDir"/package.json
    fi
  elif [ -f ../config/install_files/package_bs.json.txt ]; then
        cp ../config/install_files/package_bs.json.txt "$theDir"/package.json
  else
    if [ -f ../config/install_files/package.json.txt ]; then
      cp ../config/install_files/package.json.txt "$theDir"/package.json
    fi
  fi

fi

cd "$theDir" || exit
npm install
if [ "$useBulma" = "y" ]; then
  if [ -d "$theDir"node_modules/bulma/css ]; then
    cp -rp "$theDir"node_modules/bulma/css $thePublicDir/bulma/
  fi
  if [ -d "$theDir"node_modules/bulma-extensions/dist ]; then
    cp -rp "$theDir"node_modules/bulma-extensions/dist $thePublicDir/bulma-extensions/
  fi
  if [ -d "$theDir"node_modules/@vizuaalog/bulmajs/dist ]; then
    cp -rp "$theDir"node_modules/@vizuaalog/bulmajs/dist $thePublicDir/bulmajs/
  fi
fi
if [ "$useBootstrap" = "y" ]; then
  if [ -d "$theDir"node_modules/bootstrap/dist ]; then
    cp -rp "$theDir"node_modules/bootstrap/dist $thePublicDir/bootstrap/
  fi
fi
if [ -d "$theDir"/node_modules/html5shiv/dist ]; then
  cp -rp "$theDir"/node_modules/html5shiv/dist  $thePublicDir/html5shiv/
fi
if [ -d "$theDir"/node_modules/whatwg-fetch/dist ]; then
  cp -rp "$theDir"/node_modules/whatwg-fetch/dist $thePublicDir/whatwg-fetch/
fi
if [ -d "$theDir"/node_modules/leaflet/dist ]; then
  cp -rp "$theDir"/node_modules/leaflet/dist $thePublicDir/leaflet/
fi