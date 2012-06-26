Troubleshooting
===============

Configuration
-------------

Enabling fsockopen()
~~~~~~~~~~~~~~~~~~~~
On some PHP-Configurations the function ``fsockopen`` is disabled by default.
To enable it, open the file ``php.ini`` in ``/etc/php5/cli`` (on Debian Systems) and search for those line

.. code-block:: ini

    disable_functions = fsockopen

and remove fsockopen, that it looks like the following

.. code-block:: ini
    
    disable_functions =

Be sure that you edit the configuration file of the cli and not the one of the webserver.

Whitelisting IP-Adress
~~~~~~~~~~~~~~~~~~~~~~
If you are getting banned by the TeamSpeak3 Server very often you may need to add the IP-adress with which you are connecting to the file ``query_ip_whitelist.txt``, which is located in the TeamSpeak3 Server directory.
Open the file and enter your IP-Adress into a new line of the file (``localhost`` resp. ``127.0.0.1`` should exist already) and save it.
Normally the TeamSpeak3 Server should reload the file automatically but to he sure you can restart the TeamSpeak3 Server.