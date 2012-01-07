#!/bin/bash
# Thanks to brrrt from forum.teamspeak.com
# usage: sh secure-permissions.sh
# secures configfiles and install-folder

chmod -R o-w .
chmod 700 ./install

chmod -R g-w ./config ./install/pw.xml
find ./modules -name "*.xml" -exec chmod g-w {} \;