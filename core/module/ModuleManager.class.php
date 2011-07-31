<?php

class ms_ModuleManager
{

    private $loadedModules = Array();
    private $loadedModules_name = Array();
    private $info;
    private $viewerConfig;
    private $configname;
    private $debug;

    /**
     * Construct
     * @param type $viewerConfig array of viewer config
     * @param type $configname name of configfile
     * @param type $debug 
     */
    function __construct($viewerConfig, $configname, $debug=FALSE)
    {
        $this->viewerConfig = $viewerConfig;
        $this->debug = $debug;
        $this->configname = $configname;
    }

    /**
     * Loads a module
     * @param type $name modulename can be an array or a single name
     * @return name 
     */
    public function loadModule($name)
    {
        // Checks if $name is an array
        if (is_array($name))
        {
            foreach ($name as $mod)
            {
                $this->loadModule($mod);
            }
            return true;
        }

        // Checks if module is already loaded
        if ($this->moduleIsLoaded($name)) return $this->getModule($name);

        // Checks if module exists
        if ($this->moduleExists($name))
        {
            require_once(s_root . 'modules/' . $name . '/' . $name . '.php');

            // Loads global module-config
            if (file_exists(s_root . 'modules/' . $name . '/' . $name . '.xml'))
            {
                $config_modul = parseConfigFile(s_root . 'modules/' . $name . '/' . $name . '.xml', true);
            }

            $viewerConfigPath = s_root . 'config/' . $this->configname . '.xml';

            // Loads viewer-config as simplexml-object
            if (file_exists($viewerConfigPath))
            {
                $xmlconfig = simplexml_load_file($viewerConfigPath);
            }

            $config = Array();

            // Write Webviewer-Config in array
            foreach ($this->viewerConfig as $key => $value)
            {
                if (isset($config[$key])) continue;
                $config[$key] = $value;
            }

            // Write global Module-Config in array
            foreach ($config_modul as $key => $value)
            {
                if (isset($config[$key])) continue;
                $config[$key] = $value;
            }

            // Write local Module-Config in array
            foreach ($xmlconfig->module as $module)
            {
                foreach ($module->attributes() as $key => $value)
                {
                    if ((string) $key == "name" && (string) $value = $name)
                    {
                        foreach ($module as $key => $value)
                        {
                            switch ($value)
                            {
                                case "true":
                                    (boolean) $value = (boolean) TRUE;
                                    break;
                                case "false":
                                    (boolean) $value = (boolean) FALSE;
                                    break;
                                case "none":
                                    $value = NULL;
                                    break;
                                default:
                                    (string) $value = (string) $value;
                                    break;
                            }

                            $config[$key] = $value;
                        }
                    }
                }
            }


            $lang = NULL;
            $module = new $name($config, $lang, $this);
            $this->loadedModules[$name] = $module;
            $module->init();
            if (isset($this->info))
            {
                $module->setInfo($this->info);
            }

            return $module;
        }
        else
        {
            if ($this->debug)
            {
                echo("cannot load Module $name\n");
            }
            return false;
        }
    }

    /**
     * Checks if a module exists
     * @param type $name
     * @return type 
     */
    public function moduleExists($name)
    {
        return ((file_exists(s_root . 'modules/' . $name . '/' . $name . '.xml')) && file_exists(s_root . 'modules/' . $name . '/' . $name . '.php'));
    }

    /**
     * Checks if a module is already loaded
     * @param type $name
     * @return type 
     */
    public function moduleIsLoaded($name)
    {
        return (array_key_exists($name, $this->loadedModules));
    }

    public function getModule($name)
    {
        return $this->loadedModules[$name];
    }

    public function setInfo($info)
    {
        $this->info = $info;
        foreach ($this->loadedModules as $mod)
        {
            $mod->setInfo($this->info);
        }
    }

    public function getHeaders()
    {
        $head = '';
        foreach ($this->loadedModules as $module)
        {
            $head .= $module->getHeader();
        }
        return $head;
    }

    public function getFooters()
    {
        $foot = '';
        foreach ($this->loadedModules as $module)
        {
            $foot .= $module->getFooter();
        }
        return $foot;
    }

    public function triggerEvent($e)
    {
        $out = '';
        foreach ($this->loadedModules as $mod)
        {
            $out .= $mod->onEvent($e);
        }
        return $out;
    }

}

