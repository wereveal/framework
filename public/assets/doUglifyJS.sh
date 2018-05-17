#!/usr/bin/env bash
cd js
for thing in $(ls *.js)
do
uglifyjs $thing --compress --mangle --output min/$thing
done
cd ..