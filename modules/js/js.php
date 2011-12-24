<?php

/**
 * This file is part of TeamSpeak3 Webviewer.
 *
 * TeamSpeak3 Webviewer is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * TeamSpeak3 Webviewer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with TeamSpeak3 Webviewer. If not, see http://www.gnu.org/licenses/.
 */
class js extends ms_Module
{

    private $text;
    private $ajaxEnabled;
    public $js_sent;
    public $ajaxJS;

    function init()
    {
        $this->js_sent = false;
        $this->text = '';
        $this->ajaxJS = array();
        $this->ajaxEnabled = $this->config['ajaxEnabled'];
    }

    public function loadJS($text, $type = 'file', $cc = NULL)
    {
        if (!$this->js_sent)
        {
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
                            $this->text .= "<script src=\"" . $text . "\" type=\"text/javascript\"></script>\r\n";
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
                            $this->text .= "<!--[if " . $cc . "]>" . '<script type="text/javascript" src="' . $text . '"></script><![endif]-->';
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
                        $this->text .= "<script type=\"text/javascript\">
				/* <![CDATA[ */
				$text
				/* ]]> */
				</script>";
                    }
                    break;
            }
        }
    }

    private function onSend()
    {
        $this->text .= '<script type="text/javascript">/* <![CDATA[ */ $(document).ready(function(){ $(document).trigger("ready"); }) /* ]]> */</script>';
    }

    public function onHtmlStartup()
    {
        if (!$this->mManager->moduleIsLoaded('htmlframe') && !$this->js_sent)
        {
            $this->js_sent = true;
            $this->onSend();
            return $this->text;
        }
    }

    public function onHead()
    {
        if (!$this->js_sent)
        {
            $this->js_sent = true;
            $this->onSend();
            return $this->text;
        }
    }

}
