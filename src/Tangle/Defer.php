<?php

namespace Tangle;

class DeferCallback
{
    public $paused = 0;

    public function __construct($callback, $callbackArgs = array(), $errback, $errbackArgs = array())
    {
        $this->callback = $callback,
        $this->callbackArgs = $callbackArgs,
        $this->errback = $errback,
        $this->errbackArgs = $errbackArgs,
    }

    public function runCallback()
    {
        try {
            call_user_func_array($this->callback, $this->callbackArgs);
        } catch {
            call_user_func_array($this->errback, $this->errbackArgs);
        }
    }

    public function pause()
    {
        $this->paused = $this->paused + 1;
    }

    public function unpause()
    {
        $this->paused = $this->paused - 1;
        if ($this->paused) {
            return;
        } else {
            $this->runCallback();
        }
    }
}

class Deferred
{
    public function __construct()
    {
        $this->callbacks = array();
    }

    public function addCallback($callback, $callbackArgs = array(), $errback, $errbackArgs = array())
    {
        array_push($this->callbacks, array(
            'callback' => $callback,
            'callbackArgs' => $callbackArgs,
            'errback' => $errback,
            'errbackArgs' => $errbackArgs,
        );
    }

    public function addBoth($callback, $callbackArgs = array())
    {
        array_push($this->callbacks, array(
            'callback' => $callback,
            'callbackArgs' => $callbackArgs,
            'errback' => $callback,
            'errbackArgs' => $callbackArgs,
        );
    }

    public function _runCallbacks()
    {
        while (!empty($this->callbacks)) {
            $current = $this->callbacks[0];
            if ($current->paused) {
                return;
            } else {
                $current->runCallback();
            }
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
