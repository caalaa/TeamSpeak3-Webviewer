<?php

class channelHiding extends ms_Module
{
    protected $jsModule;
    
    public function init() {
        $this->jsModule = $this->mManager->loadModule('js');
        $this->mManager->loadModule('jQueryUI');
    }

    function onInfoLoaded()
    {   
        
        $append_config = isset($_GET['config']) ? " , config: '".$_GET['config']."' " : "";
        $ops = "var channelHiding_ops = {\r\n";
        $managevars = Array();
        $hidden = Array();
        if ($this->config['remember_hidden_chans'])
        {
            if (isset($_GET['config']))
            {
                if(isset($_SESSION['dataManager'][$_GET['config']]['channelHiding'])) {
                    $managevars = $_SESSION['dataManager'][$_GET['config']]['channelHiding'];
                }
                
            }
            else {
                if(isset($_SESSION['dataManager']['channelHiding'])) {
                    $managevars = $_SESSION['dataManager']['channelHiding'];
                }
            }
            
            
            if($managevars) {
                foreach ($managevars as $to_hide => $value)
                {
                    if ($to_hide != '')
                    {
                        $ops .= "'" . htmlspecialchars($to_hide, ENT_QUOTES) . "': true,";
                        $hidden[] = $to_hide;
                    }
                }
            }

        }

        if ($this->config['hide_empty_chans'])
        {
            foreach ($this->info['channellist'] as $key => $channel)
            {
                $parent = $channel->getParent();
                if (!empty($hidden) && $parent !== Null && in_array($parent['cid'], $hidden))
                {
                    $hidden[] = $channel['cid'];
                }
                if ($channel->isEmpty() && $channel->has_childs() && !(!empty($hidden) && !(in_array($channel['cid'], $hidden))))
                {
                    $ops .= "'" . htmlspecialchars("channel_".$channel['cid'], ENT_QUOTES) . "': true,";
                    $hidden[] = $channel['cid'];
                }
            }
            
            if (!is_numeric($this->config['fadeIn_time']) && !in_array($this->config['fadeIn_time'], Array('slow', 'fast')))
            {
                $this->config['fadeIn_time'] = '400';
            }
            if (!is_numeric($this->config['fadeOut_time']) && !in_array($this->config['fadeOut_time'], Array('slow', 'fast')))
            {
                $this->config['fadeOut_time'] = '400';
            }
            
        }
        if ($this->config['remember_hidden_chans']  || $this->config['hide_empty_chans'])
            {
                $ops = rtrim($ops, ",");
            $ops .= "};\r\n";
                $this->jsModule->loadJS($ops . "$(document).ready(function() {

										$('.channel').each( function() {

											if(channelHiding_ops[$(this).attr('id')] == true) {

												var ms_chan_con = $(this).children('.chan_content');
												ms_chan_con.siblings().fadeOut(0);
                                                                                                ms_chan_con.children('.arrow').switchClass('arrow-normal', 'arrow-hidden', 500);
												ms_chan_con.attr('is_hidden','true');
											}
										});
										$('.spacer').each( function() {
											if(channelHiding_ops[$(this).attr('id')] == true) {
												var ms_chan_con = $(this).children('.spacer_con');
												ms_chan_con.siblings().fadeOut(0);
                                                                                                ms_chan_con.children('.arrow').switchClass('arrow-normal', 'arrow-hidden', 500);
												ms_chan_con.attr('is_hidden','true');
											}
										});});", 'text');
            }
            
            $this->jsModule->loadJS("$(document).ready(function() { 
											$('.chan_content').click(function() {
											var ms_id = $(this).parent().attr('id');
											if($(this).attr('is_hidden') == 'true') {
												$(this).siblings().fadeIn(" . $this->config['fadeIn_time'] . ");			
                                                                                                $(this).children('.arrow').switchClass('arrow-hidden', 'arrow-normal', 500);
												$(this).attr('is_hidden','false');
												$.get('" . s_http . "dataManager.php',{action: 'delete', field: 'channelHiding', id: ms_id ".$append_config."});
											}
											else{
												$(this).siblings().fadeOut(" . $this->config['fadeOut_time'] . ");
                                                                                                $(this).children('.arrow').switchClass('arrow-normal', 'arrow-hidden', 500);
												$(this).attr('is_hidden','true');
												$.get('" . s_http . "dataManager.php',{action: 'save', field: 'channelHiding', id: ms_id, data: 'true' ".$append_config."});
											}
										});
										$('.spacer_con').click(function() {
											var ms_id = $(this).parent().attr('id');
											if($(this).attr('is_hidden') == 'true') {
												$(this).siblings().fadeIn(" . $this->config['fadeIn_time'] . ");											
                                                                                                $(this).children('.arrow').switchClass('arrow-hidden', 'arrow-normal', 500);
												$(this).attr('is_hidden','false');
												$.get('" . s_http . "dataManager.php',{action: 'delete', field: 'channelHiding', id: ms_id ".$append_config."});
											}
											else{
												$(this).siblings().fadeOut(" . $this->config['fadeOut_time'] . ");
                                                                                                $(this).children('.arrow').switchClass('arrow-normal', 'arrow-hidden', 500);
												$(this).attr('is_hidden','true');
												$.get('" . s_http . "dataManager.php',{action: 'save', field: 'channelHiding', id: ms_id, data: 'true' ".$append_config."});
											}
										});
});", 'text');
        }

    

}

