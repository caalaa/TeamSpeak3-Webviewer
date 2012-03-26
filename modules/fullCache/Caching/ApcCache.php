<?php

require_once s_root . 'modules/fullCache/Caching/CachingInterface.php';

/**
 * Provides a caching-class for the APC-Cache
 * @author Maxe
 * @since 1.4
 */
class ApcCache implements CachingInterface
{

    /**
     * If APC-Extension is available
     * @var booalean
     */
    protected $isAvailable;

    /**
     * Time in seconds the viewer should be cached
     * @var int 
     */
    protected $cacheTime;

    /**
     * Constructor
     * @param int $cacheTime Cache time in seconds
     * @throws RuntimeException If APC-Cache is not loaded
     */
    function __construct($cacheTime = 180)
    {
        $this->cacheTime = $cacheTime;
        if (!extension_loaded('apc'))
        {
            $this->isAvailable = false;
            throw new RuntimeException("APC-Cache is not available on the server.");
        }
        else
        {
            $this->isAvailable = true;
        }
    }

    public function cache($key, $data)
    {
        $this->checkAvailability();
        return apc_store($key, serialize($data), $this->cacheTime);
    }

    public function flush($key)
    {
        $this->checkAvailability();
        return apc_delete($key);
    }

    public function flushCache()
    {
        $this->checkAvailability();
        return apc_clear_cache();
    }

    public function getCache($key)
    {
        $this->checkAvailability();
        $data = apc_fetch($key);
        if (is_bool($data)) return $data;
        else return unserialize($data);
    }

    public function isCached($key)
    {
        $this->checkAvailability();
        return apc_exists($key);
    }

    protected function checkAvailability()
    {
        if (!$this->isAvailable) return false;
    }

}

?>
