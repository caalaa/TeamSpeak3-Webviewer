<?php

/* Author    : Maximilian Narr
 * Homepage  : http://maxesstuff.de
 * Email     : maxe@maxesstuff.de
 */

class stats extends ms_Module
{

    public $infos;

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

        // Load jqplot 
        $this->mManager->loadModule("js")->loadJS('libraries/jqplot/jquery.jqplot.min.js');
        $this->mManager->loadModule("style")->loadStyle('libraries/jqplot/jquery.jqplot.css');
        $this->mManager->loadModule("js")->loadJS('libraries/jqplot/plugins/jqplot.dateAxisRenderer.min.js');
        $this->mManager->loadModule("js")->loadJS(createJS("line1", $xml), 'text');

    }

    public function getFooter()
    {
        return('<script type="text/javascript">
                $.jqplot.config.enablePlugins = true;
                
                </script>
                <div class="jqplot" id="stats" style="height:400px;width:300px; "></div>
                <script type="text/javascript" src="modules/stats/js/script.js"></script>');

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
