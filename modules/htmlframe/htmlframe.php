<?php

class htmlframe extends ms_Module
{

    public function onStartup()
    {
        $html = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
                    "http://www.w3.org/TR/html4/loose.dtd">
                    <html>' . $this->mManager->triggerEvent("html") . '
                    <head>
                    <title>' . $_SERVER['SERVER_NAME'] . '</title>
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                    <meta name="description" content="TeamSpeak3 Webviewer by Maxesstuff">
                    <meta name="author" content="Maxesstuff">
                    ' . $this->mManager->triggerEvent("head") . '</head>
                    <body>' . $this->mManager->triggerEvent("body");
        return $html;
    }

    public function onShutdown()
    {
        return "</body></html>";
    }

}
