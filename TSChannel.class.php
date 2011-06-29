<?php

class TSChannel implements arrayaccess, Iterator
{

    private $channel_id;
    private $parent;
    private $channel;
    private $position;
    private $childs;

    public function __construct($channel_list, $channel_id)
    {

        $this->channel_id = $channel_id;

        foreach ($channel_list as $channel)
        {
            if (isset($channel['cid']) && $channel['cid'] == $this->channel_id)
            {
                $this->channel = $channel;
            }
            else if (isset($channel['pid']) && $channel['pid'] == $this->channel_id)
            {
                $this->childs[] = new TSChannel($channel_list, $channel['cid']);
            }
        }
        if (isset($this->channel['pid']))
        {
            foreach ($channel_list as $channel)
            {
                if ($channel['cid'] == $this->channel['pid'])
                {
                    $this->parent = $channel;
                    break;
                }
            }
        }
        else
        {
            $this->parent = NULL;
        }





        $this->rewind();

    }

    public function has_childs()
    {
        return!empty($this->childs);

    }

    public function get_childs()
    {
        return $this->childs;

    }

    public function has_clients($clientlist = NULL)
    {
        if ($this->channel['total_clients'] == 0)
        {
            return false;
        }
        $count = $this->channel['total_clients'];
        if ($clientlist != NULL)
        {
            foreach ($clientlist as $client)
            {
                if ($client['client_type'] == 1 && $client['cid'] == $this->channel['cid'])
                    $count--;
            }
        }
        if ($count == 0)
            return false;
        return true;

    }

    //returns true if the channel and no child has clients
    public function isEmpty()
    {
        if (!$this->has_childs() && !$this->has_clients())
            return true;
        if ($this->has_clients())
            return false;

        foreach ($this->get_childs() as $child)
        {
            if (!$child->isEmpty())
                return false;
        }
        return true;

    }

    public function getParent()
    {
        return $this->parent;

    }

    public function offsetSet($offset, $value)
    {

        $this->channel[$offset] = $value;

    }

    public function offsetExists($offset)
    {

        return isset($this->channel[$offset]);

    }

    public function offsetGet($offset)
    {

        if ($this->offsetExists($offset))
        {
            return $this->channel[$offset];
        }
        return NULL;

    }

    public function offsetUnset($offset)
    {

        unset($this->channel[$offset]);

    }

    public function rewind()
    {

        $this->position = 0;

    }

    public function current()
    {
        return $this->channel[$this->position];

    }

    public function key()
    {
        return $this->position;

    }

    public function next()
    {
        $this->position++;

    }

    public function valid()
    {
        return isset($this->channel[$position]);

    }
}

