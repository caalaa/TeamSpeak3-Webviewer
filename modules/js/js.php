<?php

/**
 *  This file is part of devMX TeamSpeak3 Webviewer.
 *  Copyright (C) 2011 - 2012 Max Rath and Maximilian Narr
 *
 *  devMX TeamSpeak3 Webviewer is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  TeamSpeak3 Webviewer is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with devMX TeamSpeak3 Webviewer.  If not, see <http://www.gnu.org/licenses/>.
 */
class js extends ms_Module
{

    public $js_sent;
    public $ajaxJS;
    protected $scripts = array();
    protected $jsOptions = array();
    private $ajaxEnabled;

    const CONFIG_VAR_NAME = "tswv";

    function init()
    {
        $this->js_sent = false;
        $this->ajaxJS = array();
        $this->ajaxEnabled = $this->config['ajaxEnabled'];
        
        // Add s_http and s_root for javascript
        $this->loadJSVar("s_http", s_http);
        $this->loadJSVar("s_root", s_root);
    }

    /**
     * Loads javascript 
     * @param string $text Javascript to load
     * @param string $type Type of the script, 'file' specifies that it is a file
     * @param string $cc Conditional Tags for browsers 
     */
    public function loadJS($text, $type = 'file', $cc = NULL)
    {
        if (!$this->js_sent)
        {
            $script = "";

            switch ($type)
            {
                case 'file':
                    if ($cc == NULL)
                    {
                        if ($this->ajaxEnabled)
                        {
                            $this->ajaxJS["src"][] = $text;
                        }
                        else
                        {
                            $script = "<script src=\"" . $text . "\" type=\"text/javascript\"></script>\r\n";
                        }
                    }
                    else
                    {
                        if ($this->ajaxEnabled)
                        {
                            $this->ajaxJS["src"][] = $text;
                        }
                        else
                        {
                            $script = "<!--[if " . $cc . "]>" . '<script type="text/javascript" src="' . $text . '"></script><![endif]-->';
                        }
                    }
                    break;
                default:
                    if ($this->ajaxEnabled)
                    {
                        $this->ajaxJS["txt"][] = "/* <![CDATA[ */
				$text
				/* ]]> */";
                    }
                    else
                    {
                        $script = "<script type=\"text/javascript\">
				/* <![CDATA[ */
				$text
				/* ]]> */
				</script>";
                    }
                    break;
            }

            if (!in_array($script, $this->scripts)) $this->scripts[] = $script;
        }
    }

    /**
     * Adds a key value pair to the javascript options output
     * @param string $key Identifier
     * @param string $value Value
     * @author Maximilian Narr
     * @since 1.4
     */
    public function loadJSVar($key, $value)
    {
        $this->jsOptions[$key] = $value;
    }

    /**
     * Adds the javascript custom options vars into $this->scripts 
     * @author Maximilian Narr
     * @since 1.4
     */
    private function prepareJSVars()
    {
        $this->loadJS("var tswv = " . json_encode($this->jsOptions) . ";", 'text');
    }

    public function onHtmlStartup()
    {
        if (!$this->mManager->moduleIsLoaded('htmlframe') && !$this->js_sent)
        {
            $this->prepareJSVars();
            $this->js_sent = true;
            return implode("", $this->scripts);
        }
    }

    public function onHead()
    {
        if (!$this->js_sent)
        {
            $this->prepareJSVars();
            $this->js_sent = true;
            return implode("", $this->scripts);
        }
    }

}
