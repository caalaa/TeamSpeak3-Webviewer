<?php
class userBar extends ms_Module
{
    public $max_clients = "";
    public $clients_online = "";
    
    function __construct($info,$config,$lang,$mm)
    {
        parent::__construct($info,$config,$lang,$mm);
        $this->mManager->loadModule("jQueryUI");
        $this->max_clients = $this->info['serverinfo']['virtualserver_maxclients'];
        $this->clients_online = $this->info['serverinfo']['virtualserver_clientsonline'] - $this->info['serverinfo']['virtualserver_queryclientsonline'];

        $per_cent = $this->clients_online / $this->max_clients * 100;
        $this->mManager->loadModule("js")->loadJS('$(document).ready(function() {
                                                   $("#userBar").progressbar({
                                                   value: '.$per_cent.'
                                                   });
                                                  });',"text");
    }

    public function getHeader()
    {
        return('<p style="font-family: sans-serif; font-size: small; margin-top:10px;">'.$this->clients_online.' '.$this->lang['max'].' '.$this->max_clients.' '.$this->lang['status'].'</p>
                <div id="userBar" style="height:15px; width:100%; margin-top:10px;"></div>');
    }
}
?>
