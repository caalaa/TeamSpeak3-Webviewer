Configuration
=============

This part of the documentation will deal with the configuration options in the installation routine.

.. note::
    
    Please note that some terms which are used here may be translated into another language which you selected before.

Mainsettings-Tab
----------------

**Serveradress**
    Description
        This is the adress of your TeamSpeak3 Server
    Values
        IP-Adress or hostname (e.g. 127.0.0.1)

**Queryport**
    Description
        The port of the TeamSpeak3 Query of your server
    Values
        10011 by default

**Serverport**
    Description
        The port of the TeamSpeak3 Server which you want to monitor
    Values
        9987 by default

**Login required**
    Description
        If a login with a Query user is required to obtain the needed rights of the TeamSpeak3 Webviewer
    Values
        Yes by default

**Username**
    Username of the Query-User

**Password**
    Password of the Query-User. Please note that the password will be saved in **plain text**. We strongly recommend creating a **seperate account** for the TeamSpeak3 Webviewer.

Modules-Tab
-----------

In the modules tab you can find two columns. In the left column are the modules displayed which are enabled, in the right column the disabled ones.
Simply drag and drop the modules. To edit the configuration of a module, click on a modules name.

Style-Tab
---------

**Download Servericons automatically**
    If custom servericons should be downloaded automatically.

**Imagepack for icons**
    The imagepack which should be used for icons if you disabled the option above.

**Stylesheet**
    The stylesheet which should be used for the webviewer.

**Display arrows**
    If arrows should be displayed next to channels like in the TeamSpeak3 Client

**Show Images**
    If all icons on the right side, like moderated icon, group icon, ... should be displayed.

**Show Country Icons**
    If country icons should be shown as in the TeamSpeak3 Client

Caching-Tab
-----------

**Enable Caching**
    If the TeamSpeak3 Webviewer should cache the data it receives. We strongly recomment enabling caching for stability reasons.

**Caching Method**
    Description
        The method which should be used to cache the data. Some options may not be available on your webserver
    Values
        - APC-Cache
        - File-Cache

**Standard cachetime**
    How long received data should be stored (in seconds). We recommend 60.

Misc-Tab
--------

**Language**
    The language of the TeamSpeak3 Webviewer

**Date/Time Format**
    The format in which dates and times should be outputted. For detailed information take a look for the `PHP date() function`_.

**Provide Usage Statistics**
    This will send the URL of your TeamSpeak3 Webviewer Installation to devMX.

.. _PHP Date() function: http://php.net/manual/en/function.date.php