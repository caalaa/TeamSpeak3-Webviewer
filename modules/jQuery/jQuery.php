<?php
	class jQuery extends ms_Module {

		private $text;

		function init() {
			$this->mManager->loadModule('js')->loadJS(stripslashes($this->config['jQuery_path']));
		 }
	}

?>
