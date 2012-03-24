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
class fullCache extends ms_Module
{
    /**
     * @var CachingInterface
     */
    protected $cache;
    
    protected $cacheKey;
    
    public function init() {
        $this->cache = $this->getCache();
        $this->cacheKey = $this->config['config_name'];
    }
    
    public function onStartup() {
        echo "checking cache".PHP_EOL;
        if($this->cache->isCached($this->cacheKey)) {
            echo "output cached".PHP_EOL;
            echo $this->cache->getCache($this->cacheKey);
            die();
        }
    }
    
    public function onCacheFlush() {
        echo "flushing cache".PHP_EOL;
        $this->cache->flush($this->cacheKey);
    }
    
    public function onShutdown($output) {
        echo "storing cache".PHP_EOL;
        $this->cache->cache($this->cacheKey, $output);
    }
    
   public function onEvent($e, $data=array()) {
        if($e !== 'Shutdown') {
            return parent::onEvent($e , $data);
        }
        else {
            $this->onShutDown($data[0]);
        }
    }
    
    protected function getCache() {
        if($this->config['enable_caching']) {
            require_once('Caching/FileCache.php');
            return new FileCache($this->config['cache_dir'], (int) $this->config['standard_cachetime']);
        }
        else {
            return new NullCache();
        }
    }
}


?>
