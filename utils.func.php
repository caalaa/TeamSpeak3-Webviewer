<?php

function sort_clients($l)
{
    return $l;
}

function ts3_check($response, $cmd='')
{
    if ($response == true)
    {
        return;
    }
    elseif (!is_array($response))
    {
        die('no response while fetching command ' . $cmd);
    }
    elseif ($response['error']['id'] != 0)
    {
        if ($cmd == '')
                die('Query Error, please check whitelist and permissions');
        else
        {
            die('Error code ' . $response['error']['id'] . ' while executing command "' . $cmd . "\"<br> Error Message:  \"" . $response['error']['msg'] . '"');
        }
    }
}

function parse_spacer($channel)
{
    $ret = Array();
    //---,...,-.-,___,-..
    if ($channel['pid'] != 0) return false;
    $spacer2 = preg_match("#^\[([rcl*]?)spacer(.*?)\](.*)#",
            $channel['channel_name'], $spacer);
    if ($spacer2 == 0)
    {
        return false;
    }
    else
    {
        //$ret = $channel;
        if (in_array($spacer[3], Array('---', '...', '-.-', '___', '-..')))
        {
            $ret['is_special_spacer'] = true;
        }
        else
        {
            $ret['is_special_spacer'] = false;
        }
        $ret['is_spacer'] = true;

        $ret['spacer_id'] = $spacer[2];
        $ret['spacer_alignment'] = $spacer[1];

        $ret['spacer_name'] = $spacer[3];
        $ret['real_name'] = $channel['channel_name'];
        $ret['cid'] = $channel['cid'];

        return $ret;
    }
}

function escape_name($name)
{
    return utf8tohtml($name, true);
}

//function from php.net
//http://de3.php.net/manual/de/function.htmlentities.php#96648
//thx to silverbeat

function utf8tohtml($utf8, $encodeTags=true)
{
    $result = '';
    for ($i = 0; $i < strlen($utf8); $i++)
    {
        $char = $utf8[$i];
        $ascii = ord($char);
        if ($ascii < 128)
        {
            // one-byte character
            $result .= ( $encodeTags) ? htmlentities($char, ENT_QUOTES) : $char;
        }
        else if ($ascii < 192)
        {
            // non-utf8 character or not a start byte
        }
        else if ($ascii < 224)
        {
            // two-byte character
            $result .= htmlentities(substr($utf8, $i, 2), ENT_QUOTES, 'UTF-8');
            $i++;
        }
        else if ($ascii < 240)
        {
            // three-byte character
            $ascii1 = ord($utf8[$i + 1]);
            $ascii2 = ord($utf8[$i + 2]);
            $unicode = (15 & $ascii) * 4096 +
                    (63 & $ascii1) * 64 +
                    (63 & $ascii2);
            $result .= "&#$unicode;";
            $i += 2;
        }
        else if ($ascii < 248)
        {
            // four-byte character
            $ascii1 = ord($utf8[$i + 1]);
            $ascii2 = ord($utf8[$i + 2]);
            $ascii3 = ord($utf8[$i + 3]);
            $unicode = (15 & $ascii) * 262144 +
                    (63 & $ascii1) * 4096 +
                    (63 & $ascii2) * 64 +
                    (63 & $ascii3);
            $result .= "&#$unicode;";
            $i += 3;
        }
    }
    return $result;
}

function get_client_image($client)
{
    global $config;

    if ($client['client_away'] == 1) return "away";
    if ($client['client_output_muted'] == 1) return "output_muted";

    if ($client['client_input_hardware'] == 0) return "mic_deactivated";

    if ($client['client_input_muted'] == 1) return "mic_muted";

    if ($client['client_is_channel_commander'] == 1) return "channel_commander";

    if ($client['client_is_talker'] == 1) return "client_talking";

    if ($client['client_is_channel_commander'] == 1 && $client['client_is_talker'] == 1)
            return "client_cm_talking";

    return "normal_client";
}

function get_servergroup_images($client, $servergroups,
        $use_serverimages = false, $servergrpimages = FALSE)
{
    $ret = Array();
    global $config;
    $client['servergroups'] = explode(",", $client['client_servergroups']);

    foreach ($client['servergroups'] as $group)
    {
        if ($use_serverimages == false)
        {

            if (isset($servergrpimages[$group]))
            {
                $ret[] = $servergrpimages[$group] . $config['image_type'];
            }
        }
        else
        {

            foreach ($servergroups as $sgroup)
            {

                if ((int) $sgroup['sgid'] == (int) $group)
                {

                    $ret[] = $sgroup['iconid'];
                }
            }
        }
    }
    return $ret;
}

function get_channelgroup_image($client, $channelgroups,
        $use_serverimages = false, $channelgrpimages = NULL)
{
    global $config;

    foreach ($channelgroups as $group)
    {
        if ($client['client_channel_group_id'] == $group['cgid'])
        {
            if ($use_serverimages == false)
            {
                if (isset($channelgrpimages[$group['cgid']]))
                {
                    return $channelgrpimages[$group['cgid']] . $config['image_type'];
                }
            }
            else
            {
                return $group['iconid'];
            }
        }
    }
}

function del_by_cid($channellist, $cid)
{
    foreach ($channellist as $key => $channel)
    {
        if (intval($channel['cid']) == intval($cid)) unset($channellist[$key]);
    }
    return $channellist;
}

// Parses a Config-File: Either a *.txt or a *.xml
function parseConfigFile($file, $xml=false)
{
    if (!$xml) return parseConfigFileText($file);
    else return parseConfigFileXML($file);
}

// Parses a Text-Config-File and returns its values as an array
function parseConfigFileText($file)
{
    if (!file_exists($file)) return false;

    $array = array();
    $fp = fopen($file, "r");
    while ($row = fgets($fp))
    {
        $row = trim($row);
        if (preg_match('#^([A-Za-z0-9_]+)\s+=\s+(.+?)(//.*)?$#D', $row, $arr))
        {
            $arr[2] = trim($arr[2]);
            $arr[1] = trim($arr[1]);
            switch ($arr[2])
            {
                case 'none':
                    $arr[2] = NULL;
                    break;
                case 'false':
                    $arr[2] = false;
                    break;
                case 'true':
                    $arr[2] = true;
            }
            $array[(string) $arr[1]] = $arr[2];
        }
    }
    fclose($fp);
    return $array;
}

// Parses a XML-Config-File and returns its values as an array
function parseConfigFileXML($file)
{
    $xml = simplexml_load_file($file);
    $config = array();

    foreach ($xml->children() as $key => $value)
    {
        switch ($value)
        {
            case "true":
                (boolean) $value = (boolean) TRUE;
                break;
            case "false":
                (boolean) $value = (boolean) FALSE;
                break;
            case "none":
                $value = NULL;
                break;
            default:
                (string) $value = (string) $value;
                break;
        }

        $config[$key] = $value;
    }
    return $config;
}

// Parses a Config-File: Either a *.txt or a *.xml
function parseLanguageFile($file, $xml=false)
{
    if (!$xml) return parseLanguageFileText($file);
    else return parseConfigFileXML($file);
}

// Parses a text-language-file and returns its values as an array
function parseLanguageFileText($file)
{
    if (!file_exists($file)) return false;

    $array = array();
    $fp = fopen($file, "r");
    while ($row = fgets($fp))
    {
        $row = trim($row);
        if (preg_match('#^([A-Za-z0-9_\s\t]+?)\s+=\s+(.*)(//.*)?$#', $row, $arr))
        {
            $arr[2] = trim($arr[2]);
            $arr[1] = trim($arr[1]);
            $array[(string) $arr[1]] = $arr[2];
        }
    }
    fclose($fp);
    return $array;
}

// Parses a xml-language-file and returns its values as an array
function parseLanguageFileXML($file)
{
    $xml = simplexml_load_file($file);
    $config = array();

    foreach ($xml->children() as $key => $value)
    {
        (string) $value = (string) $value;
        $config[$key] = $value;
    }
    return $config;
}

// function from php.net thanks to bohwaz
function unregister_globals()
{
    if (!ini_get('register_globals'))
    {
        return false;
    }

    foreach (func_get_args() as $name)
    {
        foreach ($GLOBALS[$name] as $key => $value)
        {
            if (isset($GLOBALS[$key])) unset($GLOBALS[$key]);
        }
    }
}

// Converts boolean to text
function bool2text($var)
{
    if ($var)
    {
        return 'true';
    }
    else
    {
        return 'false';
    }
}

function getUserByName($clientlist, $name)
{
    foreach ($clientlist as $client)
    {
        if ($client['client_nickname'] == $name)
        {
            return $client;
        }
    }
    return NULL;
}

function getUserByID($clientlist, $id)
{
    foreach ($clientlist as $client)
    {
        if ($client['clid'] == $id)
        {
            return $client;
        }
    }
    return NULL;
}

// Outputs an Alert
// @todo Add Code
function throwAlert($message)
{
    return;
}

// Outputs a Warning
// @todo Add Code
function throwWarning($message)
{
    return;
}

// Replaces {} in the Code with the given data
function replaceValues($file, $values, $languagefile)
{
    $data = array();
    $lang = simplexml_load_file($languagefile);

    if ($values != NULL)
    {
        (array) $data = array_merge((array) $lang, (array) $values);
    }
    else
    {
        $data = (array) $lang;
    }

    $html = file_get_contents($file);

    $matches = array();
    preg_match_all("/{.*?}/", $html, $matches);

    foreach ($matches[0] as $match)
    {
        $match_raw = $match;
        $match_norm = preg_replace("/[{}]/", "", strtolower($match_raw));

        $html = str_replace($match_raw, (string) $data[$match_norm], $html);
    }
    return $html;
}

?>