<?php

class style extends ms_Module
{

    private $text;
    public $styles_sent;

    function __construct($config, $info, $lang, $mManager)
    {
        parent::__construct($config, $info, $lang, $mManager);

        $this->styles_sent = false;

        $filepath = s_root . 'styles/' . $config['style'] . '/' . $config['style'] . '.css';
        
        if (!file_exists($filepath))
                die('style_not_found');

        $this->config['style'] = s_http . 'styles/' . $config['style'] . '/' . $config['style'] . '.css';
        $this->config['style_ie'] = s_http . 'styles/' . $config['style'] . '/' . $config['style'] . '_ie.css';
        $this->text = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $this->config['style'] . "\" >\r\n";
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

    public function onStartup()
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
