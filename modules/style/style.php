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
class style extends ms_Module
{

    private $text;
    public $styles_sent;
    
    public function init() {
        $this->styles_sent = false;
        $this->text = '';
    }

    function onStartup()
    {       

        $filepath = s_root . 'styles/' . $this->config['style'] . '/' . $this->config['style'] . '.css';
        
        if (!file_exists($filepath))
                die('style_not_found');
        
        $style = $this->config['style'];
        
        $this->config['style'] = s_http . 'styles/' . $this->config['style'] . '/' . $this->config['style'] . '.css';
        $this->config['style_ie'] = s_http . 'styles/' . $style . '/' . $style . '_ie.css';
        $this->text .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $this->config['style'] . "\" >\r\n";
        $this->text .= '<!--[if IE]><link rel="stylesheet" type="text/css" href="' . $this->config['style_ie'] . '"><![endif]-->';
    }

    public function loadStyle($text, $type='file', $cc=NULL)
    {
        if (!$this->styles_sent)
        {
            switch ($type)
            {
                case 'file':
                    $this->text .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $text . "\" >\r\n";
                    break;
                case 'cc':
                    $this->text .= '<!--[if ' . $cc . ']><style type="text/css">' . $text . '</style><![endif]-->';
                    break;
                default:
                    $this->text .= "<style type=\"text/css\">
				<!--
				$text
				-->
				</style>";
                    break;
            }
        }
    }

    public function onHtmlStartup()
    {
        if (!$this->mManager->moduleIsLoaded('htmlframe') && !$this->styles_sent)
        {
            $this->styles_sent = true;
            return $this->text;
        }
    }

    public function onHead()
    {
        if (!$this->styles_sent)
        {
            $this->styles_sent = true;
            return $this->text;
        }
    }

}
