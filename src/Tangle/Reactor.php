<?php

namespace Tangle;

use Tangle\Heapq;

class TaskCall
{
    public function __construct($runtime, $func, $args)
    {
        $this->runtime = $runtime;
        $this->func = $func;
        $this->args = $args;
    }
}

class Reactor
{
    public function __construct()
    {
        $this->squeue = new Heapq();
        $this->newtasks = array();
    }

    public function add($second, $func, $args = array())
    {
        $runtime = time() + $second;
        $task = new TaskCall($runtime, $func, $args);
        array_push($this->newtasks, $task);
    }

    public function loop()
    {
        while(True) {
            while(count($this->newtasks) != 0) {
                $this->squeue->heappush(array_pop($this->newtasks));
            }
            while($this->squeue->first() && $this->squeue->first()->runtime <= time()){
                $task = $this->squeue->heappop();
                call_user_func($task->func, $task->args);
            }
            usleep(1000000);
        }
    }
}
#$printsome = function($v) {
#    var_dump($v);
#};
#
#$reactor = new Reactor();
#$reactor->add(1, $printsome, array(2));
#$reactor->add(4, function($i){print 32;}, array(2));
#$reactor->loop();
