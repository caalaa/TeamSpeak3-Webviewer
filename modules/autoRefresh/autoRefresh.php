<?php

class autoRefresh extends ms_Module {	
	
	function onHead() {
		return '<meta http-equiv="refresh" content="'.$this->config['refresh_time'].'" >';
	}
}
