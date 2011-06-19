<?php

class serverInfo extends ms_Module
{

    public $html = "";
    public $use_tab = false;

    function __construct($info, $config, $lang, $mm)
    {
        parent::__construct($info, $config, $lang, $mm);

        require_once s_root . 'modules/serverInfo/nbbc/nbbc.php';

        $this->use_tab = $this->config['use_tab'];

        $value_format = "mb";

        if ($this->config['value_format'] != NULL || $this->config['value_format'] != "")
        {
            $value_format = $this->config['value_config'];
        }


        $bbparser = new BBCode();
        $serverinfo = $this->info['serverinfo'];


        $welcomemsg = '';

        if ($serverinfo['virtualserver_welcomemessage'] == '')
                $welcomemsg = $this->lang['no_welcome'];
        else $welcomemsg = $bbparser->Parse($serverinfo['virtualserver_welcomemessage']);

        $this->html.='<!--- START Serverinfo -->
            <div class="serverinfo"><table width="100%">
            <tr>
            <td width="33%"><h5>' . $this->lang['welcome_msg'] . '</h5><p style="border-width:1px;border-style:dotted; padding: 2px;">' . $welcomemsg . '</p><h5>' . $this->lang['channels'] . '</h5><p><span class="channelimage normal-channel">&nbsp;</span>' . $serverinfo['virtualserver_channelsonline'] . '</p></td>
            <td width="33%"><h5>' . $this->lang['version'] . '</h5><p>' . $serverinfo['virtualserver_version'] . '</p><h5>' . $this->lang['server_os'] . '</h5><p>' . $serverinfo['virtualserver_platform'] . '</p></td>
            <td width="33%"><h5>' . $this->lang['connection_info'] . '</h5><h6>' . $this->lang['total_sent'] . '</h6><p>' . $this->get_value($serverinfo['connection_bytes_sent_total'],
                        $value_format) . '</p><h6>' . $this->lang['total_received'] . '</h6><p>' . $this->get_value($serverinfo['connection_bytes_received_total'],
                        $value_format) . '</td>
            </tr>
            </table>
            </div>
            <!--- END Serverinfo -->';

        $this->mManager->loadModule("style")->loadStyle(s_http . "modules/serverInfo/style.css");

        if ($this->use_tab == true)
        {

            $this->mManager->loadModule("infoTab")->addTab($this->lang['serverinfo_tab_title'],
                    $this->html);
        }
    }

    //Konvertiert Byte zu anderen Größen
    function get_value($input, $format)
    {
        switch ($format)
        {
            case "b":
                return(number_format($input, 1) . " Bytes");
                break;
            case "kb":
                return(number_format($input / (1024), 1) . " KiB");
                break;
            case "mb":
                return(number_format($input / (1024 * 1024), 1) . " MiB");
                break;
            case "gb":
                return(number_format($input / (1024 * 1024 * 1024), 1) . " GiB");
                break;
            case "tb":
                return(number_format($input / (1024 * 1024 * 1024 * 1024), 1) . " TiB");
                break;
        }
    }

    public function getFooter()
    {

        if ($this->use_tab == false)
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