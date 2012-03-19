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
 * Compares two xml configfiles
 * @author Maximilian Narr
 * @since 1.4
 */
class configComparer
{

    protected $oldConfig;
    protected $newConfig;
    public $log;

    /**
     * Creates configComparer
     * @param SimpleXMLElement $oldConfig Old configuration
     * @param SimpleXMLElement $newConfig New configuration
     */
    function __construct($oldConfig, $newConfig)
    {
        $this->oldConfig = new SimpleXMLElement($oldConfig->asXML());
        $this->newConfig = new SimpleXMLElement($newConfig->asXML());
    }

    /**
     * Updates the configuration in $oldConfig and formats it if the DOM-Extension is available
     * @return SimpleXMLElement Updated configuration
     */
    public function updateOldFile()
    {
        foreach ($this->newConfig as $node)
        {
            // Node does not exist
            if (!$this->oldConfig->xpath($node->getName()))
            {
                $newNode = $this->oldConfig->addChild($node->getName(), $node);
                $this->log .= sprintf("Added node %s \r\n", $node->getName());
                foreach ($node->attributes() as $key => $value)
                {
                    $newNode->addAttribute($key, $value);
                    $this->log .= sprintf("Added attribute %s to node %s \r\n", $key, $node->getName());
                }
            }
        }

        if (function_exists('dom_import_simplexml'))
        {
            // Beautify xml if dom extension is enabled
            $dom = new DOMDocument('1.0');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML($this->oldConfig->asXML());
            return simplexml_load_string($dom->saveXML());
        }
        else
        {
            return $this->oldConfig;
        }
    }

}

?>
