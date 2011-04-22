<?php
class serverInfo extends ms_Module
{
    public $html = "";
    public $use_tab = false;

    function __construct($info,$config,$lang,$mm)
    {
        parent::__construct($info,$config,$lang,$mm);

        require_once s_root.'modules/serverInfo/nbbc/nbbc.php';

        $this->use_tab = $this->config['use_tab'];

        $bbparser = new BBCode();

        $serverinfo = $this->info['serverinfo'];

        $welcomemsg = '';

        if ($serverinfo['virtualserver_welcomemessage'] == '')
            $welcomemsg = $this->lang['no_welcome'];
        else
            $welcomemsg = $bbparser->Parse ($serverinfo['virtualserver_welcomemessage']);

        $this->html.='<!--- START Serverinfo -->
            <div class="serverinfo"><table width="100%">
            <tr>
            <td width="33%"><h5>'.$this->lang['welcome_msg'].'</h5><p style="border-width:1px;border-style:dotted;">'.$welcomemsg.'</p><h5>'.$this->lang['channels'].'</h5><p><img src="'.s_http.'modules/serverInfo/img/channel.png" alt="" />'.$serverinfo['virtualserver_channelsonline'].'</p></td>
            <td width="33%"><h5>'.$this->lang['version'].'</h5><p>'.$serverinfo['virtualserver_version'].'</p><h5>'.$this->lang['server_os'].'</h5><p>'.$serverinfo['virtualserver_platform'].'</p></td>
            <td width="33%"><h5>'.$this->lang['connection_info'].'</h5><h6>'.$this->lang['total_sent'].'</h6><p>'.$this->get_mb($serverinfo['connection_bytes_sent_total']).' MiB</p><h6>'.$this->lang['total_received'].'</h6><p>'.$this->get_mb($serverinfo['connection_bytes_received_total']).' MiB</td>
            </tr>
            </table></div>
            <!--- END Serverinfo -->';

        $this->mManager->loadModule("style")->loadStyle('
            /* START Serverinfo */
            .serverinfo
            {
                font-type:sans-serif;
            }

            .serverinfo img
            {
                margin-right:3px;
            }

            .serverinfo p
            {
                margin-top:0px;
                margin-bottom:5px;
                font-size:small;
            }
            .serverinfo h5
            {
                vertical-align:top;
                margin-bottom:5px;
                margin-top:0px;
            }

            .serverinfo h6
            {
                font-weight:bold;
                margin-bottom:5px;
                margin-top:0px;
            }

            .serverinfo td
            {
                vertical-align:top;
            }
            /* END Serverinfo */
            ',"text");
        
        if ($this->use_tab == true)
        {
                    
                    $this->mManager->loadModule("infoTab")->addTab($this->lang['serverinfo_tab_title'], $this->html);
        }
    }

    //Konvertiert Byte zu Mebibyte
    function get_mb($input)
    {
        return number_format($input/(1024*1024), 3);
    }

    public function getFooter()
    {
       
        if($this->use_tab == false)
        {
            return $this->html;
        }
        else
        {
            return '';
        }
    }
}
?>