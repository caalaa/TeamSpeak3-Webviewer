<?php

class legende extends ms_Module
{

    protected $conent_sent = false;

    function __construct($info, $config, $lang, $mm)
    {
        parent::__construct($info, $config, $lang, $mm);
        $this->mManager->loadModule('style')->loadStyle('.legende
                                                            {
                                                                    margin-top:10px;
                                                                    margin-left:0px;
                                                                    margin-right:0px;
                                                                    margin-bottom:0px;
                                                            }',
                'text');

        if ($this->config['use_tab'] == true)
        {
            $this->mManager->loadModule("infoTab")->addTab($this->lang['legend'],
                    $this->getText());
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
            $output .= '<div class="legende"><h4>' . $this->lang['legend'] . ':</h4>';
        }

        $output .= '<h5>' . $this->lang['servergroup'] . '</h5>';
        foreach ($this->info['servergroups'] as $sgroup)
        {
            if ($sgroup['type'] == 1)
            {
                $output .= '<div class="rechtegruppe">';
                if ($this->config['use_serverimages'] == true)
                {
                    if ($sgroup['iconid'] != 0)
                            $output .= '<p><span class="channelimage" style="background: url(\'' . $this->config['serverimages'] . $sgroup['iconid'] . '\') no-repeat transparent;" title="' . $sgroup['name'] . '">&nbsp;</span>';
                    else
                            $output .= '<p><span class="channelimage" title="' . $this->lang['no_image'] . '">&nbsp;</span>';
                }
                else
                {
                    if (isset($this->config['servergrp_images'][$sgroup['sgid']]))
                    {
                        $output .= '<p><span class="channelimage" style="background: url(\'' . $this->config['serverimages'] . $this->config['servergrp_images'][$sgroup['sgid']] . $this->config['image_type'] . '\') no-repeat transparent;">&nbsp;</span>';
                    }
                    else
                    {
                        $output .= '<p><span class="channelimage" title="' . $this->lang['no_image'] . '">&nbsp;</span>';
                    }
                }
                $output .= ' ' . $sgroup['name'] . ' (' . $sgroup['sgid'] . ')</p>';
                $output .= '</div>';
            }
        }

        $output .= '<h5>' . $this->lang['channelgroup'] . '</h5>';



        foreach ($this->info['channelgroups'] as $cgroup)
        {
            if ($cgroup['type'] == 1)
            {

                $output .= '<div class="rechtegruppe">';
                if ($this->config['use_serverimages'] == true)
                {
                    if ($cgroup['iconid'] != 0)
                            $output .= '<p><span class="channelimage" style="background: url(\'' . $this->config['serverimages'] . $cgroup['iconid'] . '\') no-repeat transparent;" title="' . $cgroup['name'] . '">&nbsp;</span>';
                    else
                            $output .= '<p><span class="channelimage" title="' . $this->lang['no_image'] . '">&nbsp;</span>';
                }
                else
                {
                    if (isset($this->config['channelgrp_images'][$cgroup['cgid']]))
                    {
                        $output .= '<p><span class="channelimage" style="background: url(\'' . $this->config['serverimages'] . $this->config['channelgrp_images'][$cgroup['cgid']] . $this->config['image_type'] . '\') no-repeat transparent;">&nbsp;</span>';
                    }
                    else
                    {
                        $output .= '<p><span class="channelimage" title="' . $this->lang['no_image'] . '">&nbsp;</span>';
                    }
                }
                $output .= ' ' . $cgroup['name'] . ' (' . $cgroup['cgid'] . ')</p>';
                $output .= '</div>';
            }
        }
        $output .= $this->mManager->triggerEvent('AfterLegend');
        return $output;
    }
}