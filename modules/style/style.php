<?php
class style extends ms_Module {

	private $text;	
	public $styles_sent;
	function __construct($config,$info,$lang,$mManager) {
		parent::__construct($config,$info,$lang,$mManager);
		$this->styles_sent = false;
		if(!file_exists('styles/'.$config['style'].'.css'))
			die('style_not_found');
		$this->config['style'] = msBASEDIR.'/styles/'.$config['style'].'.css';
		$this->text = "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$this->config['style']."\" >\r\n";
	}

	public function loadStyle($text,$type='file') {
		if(!$this->styles_sent) {
			if($type == 'file') {
				$this->text .=  "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$text."\" >\r\n";
			}
			else {
				$this->text .= "<style type=\"text/css\">
				<!--
				$text
				-->
				</style>";
			}
		}
	}
	
	public function onStartup() {
		if(!$this->mManager->moduleIsLoaded('htmlframe') && !$this->styles_sent) {
			$this->styles_sent = true;
			return $this->text;
		}
	}

	public function onHead() {
		if(!$this->styles_sent) {
			$this->styles_sent = true;
			return $this->text;
		}	
	}

}
