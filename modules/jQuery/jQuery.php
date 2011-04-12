<?php
	class jQuery extends ms_Module {

		private $text;

		function __construct($info,$config,$lang,$mm) {
			parent::__construct($info,$config,$lang,$mm);
			$this->mManager->loadModule('js')->loadJS(stripslashes($this->config['jQuery_path']));
		 }
	}

?>
