<?php

/* Author    : Maximilian Narr
 * Homepage  : http://maxesstuff.de
 * Email     : maxe@maxesstuff.de
 */

class stats extends ms_Module
{

    public $infos;
    private $html;

    function __construct($config, $info, $lang, $mManager)
    {
        parent::__construct($config, $info, $lang, $mManager);

        require_once 'modules/stats/php/utils.php';

        $this->infos = $this->info;

        if (needNewEntry())
        {
            addEntry($this->getClients());
        }

        $xml = simplexml_load_file("modules/stats/cache/data.xml");
        // Load jQuery
        $this->mManager->loadModule("jQueryUI");

        // Load jqplot 
        $this->mManager->loadModule("js")->loadJS('libraries/jqplot/jquery.jqplot.min.js');
        $this->mManager->loadModule("style")->loadStyle('libraries/jqplot/jquery.jqplot.css');
        $this->mManager->loadModule("js")->loadJS('libraries/jqplot/plugins/jqplot.dateAxisRenderer.min.js');
        $this->mManager->loadModule("js")->loadJS(createJS("line1", $xml, $this->config['locale']), 'text');
        $this->mManager->loadModule("js")->loadJS('$.jqplot.config.enablePlugins = true;', "text");
        $this->mManager->loadModule("js")->loadJs(createPlotOptions($this->config, $this->lang), "text");
        $this->mManager->loadModule("js")->loadJS('modules/stats/js/script.js');

        // Height and Width
        if ($this->config['height'] == NULL)
            $this->config['height'] = "400";

        if ($this->config['width'] == NULL)
            $this->config['width'] = "600";

        $this->html = '<div class="jqplot" id="stats" style="height:' . $this->config['height'] . 'px;width:' . $this->config['width'] . 'px; "></div>';

        // If chart should be shown in Tab
        if ($this->config['use_tab'] == true)
            $this->mManager->loadModule("infoTab")->addTab($this->lang['stats'], $this->html);

    }

    public function getFooter()
    {
        // If chart should be shown in Tab
        if ($this->config['use_tab'] == FALSE)
            return $this->html;

    }

    // Returns the number of clients online (without queryclients)
    function getClients()
    {
        $clients = 0;
        foreach ($this->infos['clientlist'] as $client)
        {
            if ((int) $client['client_type'] == 0)
                $clients++;
        }
        return $clients;

    }

}

?>
