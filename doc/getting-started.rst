Getting Started
===============


.. _Prerequisites:

Prerequisites
-------------

Please be sure that your system meets the following requirements:

    - PHP-Version 5.2.x (all older versions are not supported)
    - Apache2 Webserver or IIS (other Webservers are not officially supported but may work too)
    - PHP function ``fsockopen`` enabled

Also you need a TeamSpeak3 Query Access with the following permissions. This can also be the guest account:

    - b_virtualserver_select (use)
    - b_virtualserver_info_view (serverinfo)
    - b_virtualserver_channel_list (channellist)
    - b_virtualserver_client_list (clientlist)
    - b_virtualserver_servergroup_list (servergrouplist)
    - b_virtualserver_channelgroup_list (channelgrouplist)
    - b_serverquery_login (login)
    - b_client_info_view (clientinfo)

If you don't have these permissions the TeamSpeak3 Webviewer will not work properly.


Download
--------

Download the latest version of the TeamSpeak3 Webviewer from the `downloads page`_.
Once downloaded extract the archive and uplod it to your webspace.

Installation
------------

First, be sure that the user which runs the webserver (on Debian-Systems running Apache2 usually ``www-data``) has write permissions on these folders/ files:

    - Directories
        - config/
        - cache/
    - Files
        - install/pw.xml

If you have shell access you may use those commands to grant permissions:

.. code-block:: bash

    $ cd <your-directory>
    $ chmod -R 0770 config/
    $ chmod -R 0770 cache/
    $ chmod 0770 install/pw.xml

This works only if the user which runs the webserver owns the TeamSpeak3 Webviewer files. If he doesn't own them, you may use this script:

.. code-block:: bash

    $ cd <your-directory>
    $ chown -R www-data:www-data .

Please note that you may need to replace ``www-data`` with the user which runs the webserver under your system.
If your permissions are correct open a browser of your choice (preferably not IE) and navigate to the URL of your installation, e.g. ``http://example.com/teamspeak3-webviewer``.
Select ``Installation and Configuration`` from the top menu  [#f1]_.

Configuration
-------------

On the first run of the installation routine you will be asked to set a password for the installation routine. Enter one of your choice and remember it. You will be asked for the password each time you start the installation routine.
Also there is a check at the beginning if all :ref:`Prerequisites` are met. If there is something wrong, please fix it before continuing.

After the checking-screen you will find a list off all configuration files which you have created before. For each configuration file there are several options [#f1]_.

Edit
    With this option you will come to a page where you can edit the configuration file.

Show
    This will show you the TeamSpeak3 Server you specified in the configuration file.

Flush cache
    This will flush the cache of the appropriate configuration file. This can be a help if you experience some problems.

Delete configfile
    This will unrecoverably delete the configuration file.

Create a new configuration file
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

To create a new configuration file scroll down and enter the name into the textbox. After that click ``Create configfile`` [#f1]_.
Now you can see a configuration page where you can configurate the TeamSpeak3 Webviewer according to your needs.
For detailed information about the configuration parameters take a look for :doc:`the detailed documentation <configuration>`.

Embedding
---------

If you want to embed the TeamSpeak3 Webviewer into a webpage, there are basically two methods

Including via iFrame
~~~~~~~~~~~~~~~~~~~~

To include the TeamSpeak3 Webviewer via an iFrame, use the following basic code:

.. code-block:: html
    
    <iframe src="<your-domain>/<path-to-webviewer>/index.php?config=<your-configfile>" frameborder="0" scrolling="0" width="100%" height="<your-height>"></iframe>

Please replace ``<your-domain>`` with your domain (e.g. http://example.com), ``<path-to-webviewer`` with the path where the TeamSpeak3 Webviewer is installed (e.g. /softare/tsv) and ``<your-configfile>`` with the configuration file you created before. **Ommit** the fileextension.
To specify the height of the embed viewer, you can enter a pixel or em sized value in ``<your-height>``. For example enter ``500px`` if the displayed webviewer should be 500 pixels height. Additional information about iFrames is available on `W3.org`_.

There are also additional parameters available for the includes via iFrame. You can simply append them to your source url by adding ``&<parameter>=<value>``.

**lang**
    Values: 
        All languages which are available in the directory ``l10n`` (e.g. de_DE).
    Description: 
        Sets the TeamSpeak3 Webviewer to the language you provide as value. This parameter overrides all values in the configuration file

**fc**
    Values: 
        true
    Description: 
        If you use this parameter, the cache of the configuration file will be flushed. We strongly recommend using this parameter not in a production environment.
    
For example, if you want to include the configuration file ``test`` in German, you have to use this code

.. code-block:: html
    
    <iframe src="<your-domain>/<path-to-webviewer>/index.php?config=test&lang=de_DE" frameborder="0" scrolling="0" width="100%" height="<your-height>"></iframe>


Including via Ajax
~~~~~~~~~~~~~~~~~~

To use the more modern variant of embedding use the following code:

.. code-block:: html

    <script src="<your-domain>/<path-to-webviewer>/ajax.php?config=<your-configfile>&id=<your-id>&s=true" type="text/javascript"></script>
    <div id="<your-id>"><div>

Please replace ``<your-domain>`` with your domain (e.g. http://example.com), ``<path-to-webviewer`` with the path where the TeamSpeak3 Webviewer is installed (e.g. /softare/tsv) and ``<your-configfile>`` with the configuration file you created before. **Ommit** the fileextension.
Also replace the two ``<your-id>`` placeholdes with one unique value of your choice, for example ``ts-webviewer``.
Unfortunately setting the language only possible with the iFrame method.

.. _downloads page: http://devmx.de/en/software/teamspeak3-webviewer
.. _W3.org: http://www.w3schools.com/tags/tag_iframe.asp

.. [#f1] If you selected another language this text may appear in the selected language.