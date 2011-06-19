<?php

class ms_Module
{

    protected $info;
    protected $config;
    protected $lang;
    protected $mManager;

    function __construct($config, $info, $lang, $modulemanager)
    {
        $this->info = $info;
        $this->config = $config;
        $this->lang = $lang;
        $this->mManager = $modulemanager;

    }

    public function getHeader()
    {
        
    }

    public function getFooter()
    {
        
    }

    /*
     * Events thrown by viewer:
        onStartup:  after loading the modules;
        onShutdown: after all regular output;
    
     * Events thrown by standard modules
        HTMLframe Module:
            onHead   after outputting the <HEAD> tag 
            onHtml   after outputting the <HTML> tag;
            onBody   after outputting the <BODY> tag; (normally the same as getHeader())
        style Module:
            onStyle<name_of_the_style> if trigger_style in config is setted to true;
        legende Module:
            onAfterLegend   after the legend has been outputted
     */

    public function onEvent($e)
    {
        $e = ucfirst($e);
        $modname = "on$e";
        $ret = '';
        if (method_exists($this, $modname))
        {
            $ret = $this->$modname();
        }
        return $ret;

    }

}
