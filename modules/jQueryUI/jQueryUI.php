<?php
	class jQueryUI extends ms_Module {
		
                protected $styleModule;
                protected $jsModule;
            
		function init() {
			$this->mManager->loadModule('jQuery');
			$this->jsModule = $this->mManager->loadModule('js');
			$this->styleModule = $this->mManager->loadModule('style');
		}
                
                function onStartup() {
                    $this->styleModule->loadStyle(stripslashes($this->config['css_path']));
                    $this->jsModule->loadJS(stripslashes($this->config['js_path']));
                }

	}
