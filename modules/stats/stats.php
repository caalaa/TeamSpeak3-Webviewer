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
        
        if(needNewEntry())
        {
            addEntry($this->getClients());
        }

    }

    function getHeader()
    {
        echo('<pre>');
        echo(print_r($this->infos));
        echo('</pre>');

    }

    // Returns the number of clients online (without queryclients)
    function getClients()
    {
        $clients = 0;
        foreach ($this->infos['clientlist'] as $client)
        {
            if((int)$client['client_type'] == 0)
                $clients++;
        }
        return $clients;

    }

}

?>
