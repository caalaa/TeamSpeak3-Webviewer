<?php
//infoTabs module
//container for varius information like legende or serverstats.
class infoTab extends ms_Module {
	
	private $akt_tab;
	private $nav;
	private $tabs;
	
	function __construct($info,$config,$lang,$mm) {
		parent::__construct($info,$config,$lang,$mm);
		$this->akt_tab = 0;
		$this->nav = "";
		$this->tabs = "";
		$this->have_tabs = false;
		$this->mManager->loadModule('jQueryUI');
		$this->mManager->loadModule('js')->loadJS('$(document).ready(function() {
		$("#mstabs").tabs();
		});',"text");
	}
	
	function addTab($title,$content) {
		$this->have_tabs = true;
		$this->nav .= '<li style="font-size:16px;"><a href="#msInfoTab-'.$this->akt_tab.'">'.$title.'</a></li>'."\r\n";
		$this->tabs .= '<div style="font-size:14px;" id="msInfoTab-'.$this->akt_tab.'"><p>'.$content.'</div>'."\r\n";
		$this->akt_tab++;
	}
	
	function getFooter() {
		$output = "";
		if($this->have_tabs) {
			$output .= '<br><br>';
			$output .= '<div id="mstabs">';
			$output .= '<ul>';
			$output .= $this->nav;
			$output .= '</ul>';
			$output .= $this->tabs;
			$output .= '</div>';
		}
		return $output;
	}
}
	

	
	
	