<?php

/**
 * This file is part of TeamSpeak3 Webviewer.
 *
 * TeamSpeak3 Webviewer is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * TeamSpeak3 Webviewer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with TeamSpeak3 Webviewer. If not, see http://www.gnu.org/licenses/.
 */
class legend extends ms_Module
{

    protected $conent_sent = false;
    protected $styleModule;
    protected $infoTabModule;

    public function init()
    {

        $this->styleModule = $this->mManager->loadModule('style');
        $this->infoTabModule = $this->mManager->loadModule('infoTab');
    }

    function onInfoLoaded()
    {


        setL10n($this->config['language'], "ms-tsv-legend");

        $this->styleModule->loadStyle(s_http . 'modules/legend/legend.css');

        if ($this->config['use_tab'] == true)
        {
            $this->infoTabModule->addTab(__('Legend'), $this->getText());
            $this->content_sent = true;
        }
    }

    public function getFooter()
    {
        if ($this->content_sent == false)
        {
            return $this->getText();
        }
        return "";
    }

    protected function getText()
    {
        $output = '';
        $output .= $this->mManager->triggerEvent('before_legende');
        if (!$this->config['use_tab'])
        {
            $output .= '<div class="legend"><h4>' . __('Legend') . ':</h4>';
        }

        $output .= '<h5>' . __('Servergroup(s)') . '</h5>';
        foreach ($this->info['servergroups'] as $sgroup)
        {
            if ($sgroup['type'] == 1)
            {
                $output .= '<div class="rechtegruppe">';


                if ($sgroup['iconid'] != 0) $output .= '<p><span class="channelimage" style="background: url(\'' . $this->config['serverimages'] . $sgroup['iconid'] . '\') no-repeat transparent;" title="' . $sgroup['name'] . '">&nbsp;</span>';
                else $output .= '<p><span class="channelimage" title="' . __('No image') . '">&nbsp;</span>';

                $output .= ' ' . $sgroup['name'] . ' (' . $sgroup['sgid'] . ')</p>';
                $output .= '</div>';
            }
        }

        $output .= '<h5>' . __('Channelgroup(s)') . '</h5>';



        foreach ($this->info['channelgroups'] as $cgroup)
        {
            if ($cgroup['type'] == 1)
            {

                $output .= '<div class="rechtegruppe">';
                if ($cgroup['iconid'] != 0) $output .= '<p><span class="channelimage" style="background: url(\'' . $this->config['serverimages'] . $cgroup['iconid'] . '\') no-repeat transparent;" title="' . $cgroup['name'] . '">&nbsp;</span>';
                else $output .= '<p><span class="channelimage" title="' . __('No Image') . '">&nbsp;</span>';
                $output .= ' ' . $cgroup['name'] . ' (' . $cgroup['cgid'] . ')</p>';
                $output .= '</div>';
            }
        }
        $output .= $this->mManager->triggerEvent('AfterLegend');
        return $output;
    }

}