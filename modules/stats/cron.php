#!/usr/bin/php -f
<?php
// ************************************************************ \\
// EDIT THE FOLLOWING LINES THAT THEY FIT TO YOUR CONFIGURATION \\
// ************************************************************ \\
// If you want to upate only one configfile (Comment out inappropriate)
$configFiles = "devmx";

// If you want to update several configfiles (Comment out inappropriate)
//$configFiles = array("config1", "config2");
// ************************************************************ \\
// END CONFIGURATION DON'T EDIT ANYTHING BEYOND THIS LINE       \\
// ************************************************************ \\
define('s_root', "../../");

require_once '../../core/teamspeak/TSQuery.class.php';

// If several configfiles should be updated
if (isset($configFiles) && is_array($configFiles))
{
    foreach ($configFiles as $config)
    {
        updateStatsFile($config);
    }
}
// If only one configfile should be updated
else if (isset($configFiles) && $configFiles != "")
{
    updateStatsFile($configFiles);
}
else
{
    exit;
}
exit;

/**
 * Updates the statsfile for a configfile
 * @param configfile configfile
 * @author Maximilian Narr
 * @since 1.1
 */
function updateStatsFile($configfile)
{
    $configfile = str_replace(".xml", "", $configfile);
    $xml = simplexml_load_file("../../config/$configfile.xml");

    $customDir = s_root . 'cache/' . $xml->host . $xml->queryport . '/' . $xml->vserverport . '/';

    try
    {
        require_once 'php/utils.php';

        if (needNewEntry($configfile, $customDir))
        {
            $query = new TSQuery((string) $xml->host, (string) $xml->queryport);

            if (isset($xml->login_needed) && (bool) $xml->login_needed)
            {
                $login = $query->login((string) $xml->username, (string) $xml->password);
                if (isset($login['error']['msg']) && trim($login['error']['msg']) != "ok")
                {
                    $query->quit();
                    throw new Exception("Failed to login to TS Query:  " . $login['error']['msg']);
                }
            }

            $use = $query->use_by_port((string) $xml->vserverport);

            if (isset($serverinfo['error']['msg']) && trim($use['error']['msg']) != "ok")
            {
                $query->quit();
                throw new Exception("Failed to select virtualserver: " . $use['error']['msg']);
            }


            $serverinfo = $query->serverinfo();

            if (isset($serverinfo['error']['msg']) && trim($serverinfo['error']['msg']) != "ok")
            {
                $query->quit();
                throw new Exception("Failed to retrieve serverinfo: " . $serverinfo['error']['msg']);
            }

            $query->quit();

            if (isset($serverinfo['virtualserver_clientsonline']) && isset($serverinfo['virtualserver_queryclientsonline']))
            {
                addEntry((int) $serverinfo['virtualserver_clientsonline'] - (int) $serverinfo['virtualserver_queryclientsonline'], $configfile, $customDir);
            }
        }
    }
    catch (Exception $ex)
    {
        echo "devMX Webviewer Fatal Error: " . $ex->getMessage();
    }
}
?>
