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

/**
 *
 * @param type $l
 * @param string $sortType
 * @return array clients 
 */
function sort_clients($l, $sortType)
{
    switch ($sortType)
    {
        case "name" : usort($l, "compare_clients_byName");
            break;
        case "tsclient": usort($l, "compare_clients_likeTs3");
            break;
    }
    return $l;
}

function compare_clients_byName($a, $b)
{
    return strcasecmp($a['client_nickname'], $b['client_nickname']);
}

function compare_clients_likeTs3($a, $b)
{
    if ($a['client_talk_power'] > $b['client_talk_power']) return -1;
    else if ($a['client_talk_power'] < $b['client_talk_power']) return 1;
    else
    {
        if ($a['client_is_talker'] == 1 && $b['client_is_talker'] == 0) return -1;
        else if ($a['client_is_talker'] == 0 && $b['client_is_talker'] == 1) return 1;
        else return compare_clients_byName($a, $b);
    }
}

/**
 * Checks a response by the query for any errors
 * @param type $response
 * @param type $cmd
 * @return type 
 */
function ts3_check($response, $cmd = '')
{
    if ($response == true)
    {
        return;
    }
    elseif (!is_array($response))
    {
        throw new QueryNoResponseException("No response while fetching command " . $cmd);
    }
    elseif ($response['error']['id'] != 0)
    {
        if ($cmd == '')
        {
            throw new QueryCommunicationException("An error occured while executing on the query: " . $response['error']['msg']);
        }
        else
        {
            throw new QueryCommunicationException("An error occured while executing $cmd on the query: " . $response['error']['id'] . " " . $response['error']['msg']);
        }
    }
}

function parse_spacer($channel)
{
    $ret = Array();
    //---,...,-.-,___,-..
    if ($channel['pid'] != 0) return false;
    $spacer2 = preg_match("#.*\[([rcl*]?)spacer(.*?)\](.*)#", $channel['channel_name'], $spacer);
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

function utf8tohtml($utf8, $encodeTags = true)
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
    if ($client['client_output_muted'] == 1) return "output-muted";

    if ($client['client_input_hardware'] == 0) return "mic-deactivated";

    if ($client['client_input_muted'] == 1) return "mic-muted";

    if ($client['client_is_channel_commander'] == 1) return "channel-commander";

    return "normal-client";
}

function get_servergroup_icons($client, $servergroups, $returnArray = false)
{
    $ret = Array();
    $client['servergroups'] = explode(",", $client['client_servergroups']);

    foreach ($client['servergroups'] as $group)
    {
        foreach ($servergroups as $sgroup)
        {
            if (isset($sgroup['sgid']) && (int) $sgroup['sgid'] == (int) $group)
            {

                if ($returnArray)
                {
                    $ret['ids'][] = $sgroup['iconid'];
                    $ret['names'][] = $sgroup['name'];
                }
                else
                {
                    $ret[] = $sgroup['iconid'];
                }
            }
        }
    }
    return $ret;
}

function get_channelgroup_image($client, $channelgroups, $returnArray = false)
{
    global $config;

    foreach ($channelgroups as $group)
    {
        if (isset($group['cgid']) && $client['client_channel_group_id'] == $group['cgid'])
        {
            if ($returnArray)
            {
                $sgroup['iconid'] = $group['iconid'];
                $sgroup['name'] = $group['name'];
                return $sgroup;
                
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

/**
 * Gets a user by its name
 * @param type $clientlist
 * @param type $name
 * @return type 
 */
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

/**
 * Gets a user by its id
 * @param type $clientlist
 * @param type $id
 * @return type 
 */
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

?>
