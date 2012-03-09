#!/usr/bin/bash

# ++++++++++++++++++ EDIT APPROPRIATE TO YOUR CONFIGURATION +++++++++++++++
# Adapt the path that it fits to the location of the stats module folder ++
# +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
cd /var/www/testing/maxe/tswebviewer/modules/stats
# +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

php cron.php
exit 0