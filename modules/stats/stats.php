<?php

/**
 *  This file is part of devMX TeamSpeak3 Webviewer.
 *  Copyright (C) 2011 - 2012 Max Rath and Maximilian Narr
 *
 *  devMX TeamSpeak3 Webviewer is free software: you can redistribute it and/or modify
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
 *  along with devMX TeamSpeak3 Webviewer.  If not, see <http://www.gnu.org/licenses/>.
 */
class stats extends ms_Module
{

    public $infos;
    private $html;
    protected $jsModule;
    protected $styleModule;
    protected $tabModule;

    public function init()
    {

        require_once s_root . 'modules/stats/php/utils.php';

        $this->jsModule = $this->mManager->loadModule('js');
        $this->styleModule = $this->mManager->loadModule('style');
        if ($this->config['use_tab'])
        {
            $this->tabModule = $this->mManager->loadModule('infoTab');
        }
        // Load jQuery
        $this->mManager->loadModule("jQueryUI");
    }

    function onInfoLoaded()
    {

        setL10n($this->config['language'], "teamspeak3-webviewer");
        $configfile = '';

        if (!isset($_GET['config']) || $_GET['config'] == "") $configfile = "config";
        else $configfile = $_GET['config'];



        $this->infos = $this->info;

        if (needNewEntry($configfile))
        {
            addEntry($this->getClients(), $configfile);
        }

        $xml = simplexml_load_file(CACHE_DIR . "/" . "stats_$configfile.xml");

        // set min on y axes
        $this->config['min'] = (getMinClients($xml) - 1);

        // Load jqplot 
        // IE workaround
        $this->jsModule->loadJS(s_http . 'libraries/jqplot/excanvas.min.js', 'file', 'lt IE 9');
        $this->jsModule->loadJS(s_http . 'libraries/jqplot/jquery.jqplot.min.js');
        $this->styleModule->loadStyle(s_http . 'libraries/jqplot/jquery.jqplot.min.css');
        $this->jsModule->loadJS(s_http . 'libraries/jqplot/plugins/jqplot.dateAxisRenderer.min.js');
        $this->jsModule->loadJS(createJS("line1", $xml, $this->config['locale']), 'text');
        //$this->jsModule->loadJS('jQuery.jqplot.config.enablePlugins = true;', "text");
        $this->jsModule->loadJs(createPlotOptions($this->config), "text");
        $this->jsModule->loadJS(s_http . 'modules/stats/js/script.js');

        // Height and Width
        if ($this->config['height'] == NULL) $this->config['height'] = "400px";

        if ($this->config['width'] == NULL) $this->config['width'] = "600px";

        $this->html = '<div class="jqplot" id="stats" style="height:' . $this->config['height'] . ';width:' . $this->config['width'] . '; "></div>';

        // If chart should be shown in Tab
        if ($this->config['use_tab'] == true) $this->tabModule->addTab(__('Statistics'), $this->html);
    }

    public function getFooter()
    {
        // If chart should be shown in Tab
        if ($this->config['use_tab'] == FALSE) return $this->html;
    }

    // Returns the number of clients online (without queryclients)
    function getClients()
    {
        $clients = 0;
        foreach ($this->infos['clientlist'] as $client)
        {
            if ((int) $client['client_type'] == 0) $clients++;
        }
        return $clients;
    }

}

?>
