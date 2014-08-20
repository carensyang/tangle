<?php

namespace Tangle;

class Event
{
    public function __construct()
    {
        $this->event_base = event_base_new();
    }

    public function createEvent($fd, $events, $callback)
    {
        $event = event_new();
        event_set($event, $fd, $events, $callback, array($event, $this->event_base));
        event_base_set($event, $this->event_base);
        event_add($event);
    }

    public function eventLoop()
    {
        event_base_loop($this->event_base, EVLOOP_NONBLOCK);
    }
}

#$callback = function ($fd, $events, $arg)
#{
#    echo  fgets($fd);
#};
#
#$e = new Event;
#$e->createEvent(STDIN, EV_READ | EV_PERSIST, $callback);
#while(true) {
#    $e->eventLoop();
#}
