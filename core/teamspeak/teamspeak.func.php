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
 * Renders the servername
 * @global type $config
 * @global type $mManager 
 * @param type $serverinfo
 * @return string formatted html
 */
function render_server($serverinfo)
{
    global $config, $mManager;
    return "<div class=\"server\">\r\n<p  class=\"servername\">" . $mManager->triggerEvent("InServer") . " <span>" . getServerIcon($serverinfo, $config) . '<span class="serverimage image">&nbsp;</span>' . escape_name($serverinfo['virtualserver_name']) . "</span></p>\r\n";
}

/**
 * Renders the servericon
 * @since 1.0
 * @param type $serverinfo
 * @param type $config
 * @return type 
 */
function getServerIcon($serverinfo, $config)
{
    global $config;

    if (!$config['show_icons']) return '';

    if ($config['use_serverimages'] && isset($serverinfo['virtualserver_icon_id']) && $serverinfo['virtualserver_icon_id'] != 0)
    {
        return '<span class="group-image img_r" style="background-image: url(\'' . $config['serverimages'] . $serverinfo['virtualserver_icon_id'] . '\');">&nbsp;</span>';
    }
    else
    {
        return '';
    }
}

/**
 * Renders a TeamSpeak3 Client
 * @global type $config
 * @param type $clientinfo
 * @param type $servergrouplist
 * @param type $channelgrouplist
 * @return string 
 */
function render_client($clientinfo, $servergrouplist, $channelgrouplist)
{
    global $config;

    if ($clientinfo['client_type'] == 1) return '';

    $rendered = '<div class="client" id="' . $config['prefix'] . 'client_' . htmlspecialchars($clientinfo['clid'], ENT_QUOTES) . '"><p class="client-content">';
    $iconHtml = '';

    // Get servercroup icons
    $serverGroupIcons = get_servergroup_icons($clientinfo, $servergrouplist);
    foreach ($serverGroupIcons as $iconID)
    {
        if ($iconID == 0 || !$config['show_icons']) continue;
        $iconHtml = '<span class="img_r group-image" style="background: url(\'' . $config['serverimages'] . $iconID . '\') no-repeat transparent;">&nbsp;</span>' . $iconHtml;
    }

    // Get channelgroup icons
    $channelGroupIcon = get_channelgroup_image($clientinfo, $channelgrouplist);
    if ($channelGroupIcon != 0)
    {
        if ($config['show_icons'])
        {
            $iconHtml .= '<span class="img_r group-image" style="background: url(\'' . $config['serverimages'] . $channelGroupIcon . '\') no-repeat transparent;">&nbsp;</span>';
        }
    }

    // Get clienticon
    $clientIcon = $clientinfo['client_icon_id'];
    if ($clientIcon !== 0)
    {
        if ($config['show_icons'])
        {
            $iconHtml = '<span class="img_r group-image" style="background: url(\'' . $config['serverimages'] . $clientIcon . '\') no-repeat transparent;">&nbsp;</span>' . $iconHtml;
        }
    }

    // Country icon
    $country = $clientinfo['client_country'];
    if ($country != "" && $country != null && $config['show_country_icons'])
    {
        $iconHtml = '<span class="img_r group-image" style="background: url(\'' . s_http . "modules/infoDialog/flags/" . strtolower($country) . ".png" . '\') center center no-repeat;">&nbsp;</span> ' . $iconHtml;
    }

    $rendered .= $iconHtml;

    $rendered .= '<span class="clientimage ' . get_client_image($clientinfo) . '">&nbsp;</span>' . escape_name($clientinfo['client_nickname']);
    $rendered .= "\r\n</p></div>";
    return $rendered;
}

/**
 * Renders a TeamSpeak3 Channel
 * @global type $config
 * @param array $channel
 * @param type $clientlist
 * @return string 
 */
function render_channel_start($channel, $clientlist)
{
    global $config;
    $output = '';
    $channel['channel_name'] = (parse_spacer($channel) === false ? $channel['channel_name'] : parse_spacer($channel));

    if (!is_array($channel['channel_name']))
    {
        $channelimage = 'normal-channel';

        if ($channel['channel_maxclients'] != -1 && $channel['channel_maxclients'] <= $channel['total_clients'])
        {
            $channelimage = 'full';
        }
        elseif ($channel['channel_flag_password'] == 1)
        {
            $channelimage = 'locked';
        }

        if (($channel->has_childs() || $channel->has_clients($clientlist)) && $config['show_arrows'])
        {
            $output .= '<div class="channel channel_arr" id="' . $config['prefix'] . "channel_" . htmlspecialchars($channel['cid'], ENT_QUOTES) . "\">\r\n";
        }
        else
        {
            $output .= '<div class="channel channel_norm" id="' . $config['prefix'] . "channel_" . htmlspecialchars($channel['cid'], ENT_QUOTES) . "\">\r\n";
        }
        $output .= '<p class="chan_content">';

        // If channel has a channel icon
        if ($channel['channel_icon_id'] != 0 && $config['use_serverimages'] == true)
        {
            if ($config['show_icons'])
            {
                $output .= '<span class="img_r group-image" style="background: url(\'' . $config['serverimages'] . $channel['channel_icon_id'] . '\') no-repeat transparent;">&nbsp;</span>';
            }
        }


        if ($config['show_icons'])
        {
            $output .= getIsDefaultIcon($channel, $config);
        }

        // If channel is moderated
        if ($channel['channel_needed_talk_power'] > 0)
        {
            if ($config['show_icons'])
            {
                $output .= '<span class="channel-perm-image moderated img_r">&nbsp;</span>';
            }
        }

        // If channel has password
        if ($channel['channel_flag_password'] == '1')
        {
            if ($config['show_icons'])
            {
                $output .= '<span class="channel-perm-image password img_r">&nbsp;</span>';
            }
        }

        // If arrow needs to be displayed
        if (($channel->has_childs() || $channel->has_clients($clientlist)) && $config['show_arrows'])
        {
            $output .= '<span class="img_l arrow arrow-normal"></span>';
        }

        $output .= '<span class="channelimage ' . $channelimage . '">&nbsp;</span>' . escape_name($channel['channel_name']);
        $output .= "</p>\r\n";
    }
    else
    {
        if (($channel->has_childs() || $channel->has_clients($clientlist)) && $config['show_arrows'])
        {
            $output .= '<div class="spacer spacer_arr" id="' . $config['prefix'] . "channel_" . htmlspecialchars($channel['cid'], ENT_QUOTES) . '">';
        }
        else
        {
            $output .= '<div class="spacer spacer_norm" id="' . $config['prefix'] . "channel_" . htmlspecialchars($channel['cid'], ENT_QUOTES) . '">';
        }
        if ($channel['channel_name']['is_special_spacer'])
        {
            switch ($channel['channel_name']['spacer_name'])
            {
                case '---':
                    $output .= '<p class="bs spacer_con">';
                    break;
                case '...':
                    $output .= '<p class="punkt spacer_con">';
                    break;
                case '-.-':
                    $output .= '<p class="bspunkt spacer_con">';
                    break;
                case '___':
                    $output .= '<p class="linie spacer_con">';
                    break;
                case '-..':
                    $output .= '<p class="bsdpunkt spacer_con">';
                    break;
            }
            if (($channel->has_childs() || $channel->has_clients($clientlist)) && $config['show_arrows'])
            {
                $output .= '<span class="img_l arrow arrow-normal">&nbsp;</span>';
            }
            $output .= '&nbsp;';

            $output .= '</p>';
        }
        else
        {
            switch ($channel['channel_name']['spacer_alignment'])
            {

                case 'r':
                    $output .= '<p class="left spacer_con">';
                    break;
                case 'c':
                    $output .= '<p class="center spacer_con">';
                    break;
                case 'l':
                    $output .= '<p class="left spacer_con">';
                    break;
                case '*':
                    $output .= '<p class="left overflow spacer_con">';
                    break;
            }
            if (($channel->has_childs() || $channel->has_clients($clientlist)) && $config['show_arrows'])
            {
                $output .= '<span class="img_l arrow arrow-normal">&nbsp;</span>';
            }

            $output .= ( $channel['channel_name']['spacer_alignment'] == '*' ? str_repeat(escape_name($channel['channel_name']['spacer_name']), 600) : escape_name($channel['channel_name']['spacer_name'])) . "</p>\r\n";
        }
    }

    return $output;
}

/**
 * Returns the html of a home icon if the channel is the default channel
 * @param type $channel
 * @param type $config
 * @return type 
 */
function getIsDefaultIcon($channel, $config)
{
    if (isset($channel['channel_flag_default']) && $channel['channel_flag_default'] == 1)
    {
        return '<span class="group-image img_r home">&nbsp;</span>';
    }
}

/*
 * Renders the Channellist
 * @param mixed $channellist
 * @param mixed $clientlist
 * @param mixed $servergroups
 * @param mixed $channelgroups
 * @param bool $renderClientsOnly If only clients should be shown in the viewer
 * @param bool $renderChanelsWithClientsOnly If only channels should be shown with clients inside
 * @return string output
 */
function render_channellist($channellist, $clientlist, $servergroups, $channelgroups, $renderClientsOnly = false, $renderChannelsWithClientsOnly = false)
{
    static $is_rendered;

    global $config;

    $output = '';
    $clients_to_render = Array();

    foreach ($channellist as $channel)
    {
        $clients_to_render = Array();
        if (@in_array($channel['cid'], $is_rendered)) continue;

        $is_rendered[] = $channel['cid'];

        // If only clients should be rendered
        if (!$renderClientsOnly)
        {
            // Check if only channels with clients should be rendered
            if (!$renderChannelsWithClientsOnly)
            {
                $output .= render_channel_start($channel, $clientlist);
            }
            else
            {
                if (!$channel->isEmpty() && parse_spacer($channel) === false)
                {
                    $output .= render_channel_start($channel, $clientlist);
                }
            }
        }

        foreach ($clientlist as $client)
        {
            if ($client['cid'] == $channel['cid'])
            {
                $clients_to_render[] = $client;
            }
        }

        $clients_to_render = sort_clients($clients_to_render, $config['sort_method']);
        foreach ($clients_to_render as $client)
        {
            $output .= render_client($client, $servergroups, $channelgroups);
        }


        if ($channel->has_childs())
        {
    		if($renderChannelsWithClientsOnly){
				$output .= render_channellist($channel->get_childs(), $clientlist, $servergroups, $channelgroups, false, true);
			}
            // If only clients should be rendered
            if (!$renderClientsOnly && !$renderChannelsWithClientsOnly)
            {
            	$output .= render_channellist($channel->get_childs(), $clientlist, $servergroups, $channelgroups);
            }
        }

        if (!$renderClientsOnly && !$renderChannelsWithClientsOnly)
        {
            $output .= "</div>\r\n";
        }
		if (!$channel->isEmpty() && parse_spacer($channel) === false && $renderChannelsWithClientsOnly)
		{
			$output .= "</div>\r\n";
		}
    }

    return $output;
}

/**
 * Sorts the clients like in TS3 Client or per name
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

/**
 * Compare function for name bases sorting
 * @param type $a
 * @param type $b
 * @return type 
 */
function compare_clients_byName($a, $b)
{
    return strcasecmp($a['client_nickname'], $b['client_nickname']);
}

/**
 * Compare function for TS3 Client sorting
 * @param type $a
 * @param type $b
 * @return int 
 */
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

/**
 * Checks if a channel is a spacer and returns its html-code if appropriate
 * @param type $channel
 * @return boolean 
 */
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

/**
 * Converts UTF-8 to HTML
 * @param type $name
 * @return type 
 */
function escape_name($name)
{
    return utf8tohtml($name, true);
}

/**
 * Converts UTF-8 Chars to HTML
 * @author silverbeat
 * @see http://de3.php.net/manual/de/function.htmlentities.php#96648
 * @param type $utf8
 * @param type $encodeTags
 * @return type 
 */
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

/**
 * Returns the class of the client status image
 * @param type $client Client to parse
 * @return string class for the client status image
 */
function get_client_image($client)
{
    if ($client['client_away'] == 1) return "away";
    if ($client['client_output_hardware'] == 0) return "output-deactivated";
    if ($client['client_output_muted'] == 1) return "output-muted";
    if ($client['client_input_hardware'] == 0) return "mic-deactivated";
    if ($client['client_input_muted'] == 1) return "mic-muted";
    if ($client['client_is_channel_commander'] == 1) return "channel-commander";
    if ($client['client_is_channel_commander'] == 1 && $client['client_flag_talking'] == 1) return "channel-commander-talking";
    if ($client['client_flag_talking']) return "talking-client";
    return "normal-client";
}


/**
 * Returns the ID of a servergroups icon
 * @param array $client
 * @param type $servergroups
 * @param type $returnArray
 * @return type 
 */
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

/**
 * Returns the ID of a channelgroups icon
 * @global type $config
 * @param type $client
 * @param type $channelgroups
 * @param type $returnArray
 * @return type 
 */
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
