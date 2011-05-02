<?php

class js extends ms_Module
{

    private $text;
    public $js_sent;

    function __construct($config, $info, $lang, $mManager)
    {
        parent::__construct($config, $info, $lang, $mManager);
        $this->js_sent = false;
        $this->text = '';
    }

    public function loadJS($text, $type='file', $cc=NULL)
    {
        if (!$this->js_sent)
        {
            switch ($type)
            {
                case 'file':
                    if ($cc == NULL)
                    {
                        $this->text .= "<script src=\"" . $text . "\" type=\"text/javascript\"></script>\r\n";
                    }
                    else
                    {
                        $this->text .= "<!--[if ".$cc."]>".'<script type="text/javascript" src="'.$text.'"></script><![endif]-->';
                    }
                    break;
                default:
                    $this->text .= "<script type=\"text/javascript\">
				//<!--
				$text
				//-->
				</script>";
                    break;
            }
        }
    }

    public function onStartup()
    {
        if (!$this->mManager->moduleIsLoaded('htmlframe') && !$this->js_sent)
        {
            $this->js_sent = true;
            return $this->text;
        }
    }

    public function onHead()
    {
        if (!$this->js_sent)
        {
            $this->js_sent = true;
            return $this->text;
        }
    }

}
