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

    private $ajaxEnabled;
    public $js_sent;
    protected $scripts = array();
    public $ajaxJS;

    function init()
    {
        $this->js_sent = false;
        $this->ajaxJS = array();
        $this->ajaxEnabled = $this->config['ajaxEnabled'];
    }

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
            
            if(!in_array($script, $this->scripts))
                    $this->scripts[] = $script;
        }
    }

    private function onSend()
    {
        $this->scripts[]  = '<script type="text/javascript">/* <![CDATA[ */ jQuery(document).ready(function(){ jQuery(document).trigger("ready"); }) /* ]]> */</script>';
    }

    public function onHtmlStartup()
    {
        if (!$this->mManager->moduleIsLoaded('htmlframe') && !$this->js_sent)
        {
            $this->js_sent = true;
            $this->onSend();
            return implode("", $this->scripts);
        }
    }

    public function onHead()
    {
        if (!$this->js_sent)
        {
            $this->js_sent = true;
            $this->onSend();
            return implode("", $this->scripts);
        }
    }

}
