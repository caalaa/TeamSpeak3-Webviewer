#!/bin/bash
# usage: sh setup-permissions.sh
#
# makes configfiles and cache writeable by the webserver:

read -p "please enter the webservers username (default: www-data): " wwwuser
wwwuser=${wwwuser:-www-data}

test=`id -u $wwwuser` || exit 1

chmod -R 0755 ./install

chmod -R g+w ./config ./cache ./install/pw.xml
find ./modules -name "*.xml" -exec chmod g+w {} \;

chgrp -R $wwwuser ./config ./cache ./install/pw.xml
find ./modules -name "*.xml" -exec chgrp $wwwuser {} \;
