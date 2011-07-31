<?php

/**
 *  This file is part of TeamSpeak3 Webviewer.
 *
 *  TeamSpeak3 Webviewer is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  TeamSpeak3 Webviewer is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with TeamSpeak3 Webviewer.  If not, see <http://www.gnu.org/licenses/>.
 */

class serverInfo extends ms_Module
{

    public $html = "";
    public $use_tab = false;
    
    protected $styleModule;
    protected $tabModule;
    
    public function init() {
      
        require_once s_root . 'modules/serverInfo/nbbc/nbbc.php';
        $this->use_tab = $this->config['use_tab'];
        $this->styleModule = $this->mManager->loadModule('style');
        if($this->use_tab) {
            $this->tabModule = $this->mManager->loadModule('infoTab');
        }
    }

    function onInfoLoaded()
    {
       setL10n($this->config['language'], "ms-tsv-serverInfo");
      
        $value_format = "mb";

        if ($this->config['value_format'] != NULL || $this->config['value_format'] != "")
        {
            $value_format = $this->config['value_format'];
        }


        $bbparser = new BBCode();
        $serverinfo = $this->info['serverinfo'];


        $welcomemsg = '';
        
        if ($serverinfo['virtualserver_welcomemessage'] == '')
                $welcomemsg = __('no welcomemessage');
        else $welcomemsg = $bbparser->Parse($serverinfo['virtualserver_welcomemessage']);

        $this->html.='<!--- START Serverinfo -->
            <div class="serverinfo"><table width="100%">
            <tr>
            <td width="33%"><h5>' . __('Welcomemessage') . '</h5><p style="border-width:1px;border-style:dotted; padding: 2px;">' . $welcomemsg . '</p><h5>' . __('Channels') . '</h5><p><span class="channelimage normal-channel">&nbsp;</span>' . $serverinfo['virtualserver_channelsonline'] . '</p></td>
            <td width="33%"><h5>' . __('Version') . '</h5><p>' . $serverinfo['virtualserver_version'] . '</p><h5>' . __('Server OS') . '</h5><p>' . $serverinfo['virtualserver_platform'] . '</p></td>
            <td width="33%"><h5>' . __('Connectiondetails') . '</h5><h6>' . __('total sent') . '</h6><p>' . $this->get_value($serverinfo['connection_bytes_sent_total'],
                        $value_format) . '</p><h6>' . __('total received') . '</h6><p>' . $this->get_value($serverinfo['connection_bytes_received_total'],
                        $value_format) . '</td>
            </tr>
            </table>
            </div>
            <!--- END Serverinfo -->';

        $this->styleModule->loadStyle(s_http . "modules/serverInfo/style.css");

        if ($this->use_tab == true)
        {

            $this->tabModule->addTab(__('Serverinformation'),
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