<?php
/**
 *
 * @author drak3
 */
class NullCache implements CachingInterface
{
    public function cache( $key , $data )
    {
    }

    public function flush( $key )
    {
    }

    public function flushCache()
    {
        
    }

    public function getCache( $key )
    {
        return null;
    }

    public function isCached( $key )
    {
        return false;
    }

}

?>
