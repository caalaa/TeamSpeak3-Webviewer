#!/bin/bash
# usage: sh secure-permissions.sh
#
# secures configfiles and install-folder

chmod -R 750 ./install

chmod -R g-w ./config ./install/pw.xml
find ./modules -name "*.xml" -exec chmod g-w {} \;

