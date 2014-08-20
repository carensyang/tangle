<?php
class Ioc
{
    public $instances = array();

    public function set($name, Closure $callback)
    {
        $this->instances[$name] = call_user_func($callback);
    }

    public function get($name)
    {
        if(!isset($this->instances[$name]))
        {
            return null;
        }
        return $this->instances[$name];
    }
}
