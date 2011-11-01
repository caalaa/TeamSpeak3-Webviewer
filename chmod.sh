#!/bin/bash

### Cd to the directory if the webviewer and run this script via
### cd YOUR_VIEWER_DIRECTORY
### sh ./chmod.sh
### to set chmod automatically to all necessary files

chmod -R 0777 cache/*
chmod -R 0777 config/*
chmod 0777 install/pw.xml