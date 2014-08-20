<?php

namespace Tangle;

use Tangle\Heapq;
use Tangle\Event;

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
    public $event;

    public function __construct()
    {
        $this->event = new Event();
        $this->squeue = new Heapq();
        $this->newtasks = array();
    }

    public function callLater($second, $func, $args = array())
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
                call_user_func_array($task->func, $task->args);
            }
            // event loop
            $this->event->eventLoop();
            usleep(10000);
        }
    }
}

#$printsome = function($v) {
#    var_dump($v);
#};
#
#$callback = function ($fd, $events, $arg)
#{
#    echo  fgets($fd);
#};
#
#include("Heapq.php");
#include("Event.php");
#$reactor = new Reactor();
#$reactor->callLater(5, $printsome, array(2));
#$reactor->event->createEvent(STDIN, EV_READ | EV_PERSIST, $callback);
#$reactor->callLater(10, function($i){print 32;}, array(2));
#$reactor->loop();
