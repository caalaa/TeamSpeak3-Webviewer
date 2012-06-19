#!/bin/bash

FILES=`find . -name "*.po"`

for f in $FILES 
do
	name=${f%\.*}
	msgfmt $f -o $name.mo
done
