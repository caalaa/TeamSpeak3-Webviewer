<?php
	class jQueryUI extends ms_Module {
		private $text;
		function __construct($info,$config,$lang,$modulmanager) {
			parent::__construct($info,$config,$lang,$modulmanager);
			$this->mManager->loadModule('jQuery');
			$this->mManager->loadModule('js')->loadJS(stripslashes($this->config['js_path']));
			$this->mManager->loadModule('style')->loadStyle(stripslashes($this->config['css_path']));
		}

	}
