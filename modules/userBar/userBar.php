<?php
class userBar extends ms_Module
{
    public $max_clients = "";
    public $clients_online = "";
    protected $jsModule;
    protected $styleModule;
    
    public function init() {
       
        $this->mManager->loadModule("jQueryUI");
        $this->jsModule = $this->mManager->loadModule('js');
        $this->styleModule = $this->mManager->loadModule('style');
    }
    
    function onInfoLoaded()
    {
        
        
        //L10N
         setL10n($this->config['language'], "mssetL10n-tsv-userBar");
        
        
        $this->max_clients = $this->info['serverinfo']['virtualserver_maxclients'];
        $this->clients_online = $this->info['serverinfo']['virtualserver_clientsonline'] - $this->info['serverinfo']['virtualserver_queryclientsonline'];

        $per_cent = $this->clients_online / $this->max_clients * 100;
        $this->jsModule->loadJS('$(document).ready(function() {
                                                   $("#userBar").progressbar({
                                                   value: '.$per_cent.'
                                                   });
                                                  });',"text");
        
        $this->styleModule->loadStyle('#userBar
            {
                margin-bottom:5px;
            }', "text");
    }

    public function getHeader()
    {
         setL10n($this->config['language'], "mssetL10n-tsv-userBar");
        return('<p style="font-family: sans-serif; font-size: small;">'.$this->clients_online.' '.__('of').' '.$this->max_clients.' '.__('are currently online').'</p>
                <div id="userBar" style="height:15px; width:100%; margin-top:10px;"></div>');
    }
}
?>
