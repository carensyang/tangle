<?php

namespace Tangle;

class DeferCallback
{
    public function __construct($callback, $callbackArgs = array(), $errback, $errbackArgs = array())
    {
        $this->callback = $callback;
        $this->callbackArgs = $callbackArgs;
        $this->errback = $errback;
        $this->errbackArgs = $errbackArgs;
    }

    public function runCallback($result)
    {
        try {
            return call_user_func_array($this->callback, array_merge($result, $this->callbackArgs));
        } catch (Exception $e) {
            return call_user_func_array($this->errback, $this->errbackArgs);
        }
    }

}

class DeferList
{    
    public $paused = 0;

    public function __construct()
    {
        $this->callbacks = array();
        $this->current_result = array();
    }

    public function addCallback($callback, $callbackArgs = array(), $errback, $errbackArgs = array())
    {
        $callbackArgs = array_merge($this->current_result, $callbackArgs);
        $errbackArgs = array_merge($this->current_result, $errbackArgs);
        $defercall = new DeferCallback($callback, $callbackArgs, $errback, $errbackArgs);
        array_push($this->callbacks, $defercall);
    }

    public function addBoth($callback, $callbackArgs = array())
    {
        $callbackArgs = array_merge($this->current_result, $callbackArgs);
        $defercall = new DeferCallback($callback, $callbackArgs, $callback, $callbackArgs);
        array_push($this->callbacks, $defercall);
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
            $this->_runCallbacks();
        }
    }

    public function startRunCallbacks($arg)
    {
        if (is_array($arg)) {
            $this->current_result = $arg;
        } else {
            $this->current_result = array($arg);
        }
        $this->_runCallbacks();
    }

    public function _runCallbacks()
    {
        while (!empty($this->callbacks)) {
            $current = $this->callbacks[0];
            if ($this->paused) {
                return;
            } else {
                $result = $current->runCallback($this->current_result);
                $this->current_result = array($result);
                array_shift($this->callbacks);
            }
        }
    }

    public function callback($arg)
    {
        $this->startRunCallbacks($arg);
    }
}

#$printsome = function($v) {
#    var_dump($v);
#    return $v;
#};
#
#$add = function($a, $b) {
#    return $a + $b;
#};
#$sub = function($a, $b, $deferred, $reactor) {
#    $deferred->pause();
#    $unp = function() use ($deferred) {
#        $deferred->unpause();
#    };
#    $reactor->add(5, $unp);
#    return $a - $b;
#};
#
#include("Reactor.php");
#include("Heapq.php");
#$reactor = new Reactor();
## 2 + 4 - 10 + 4
## 算完2+4后5秒以后再执行-10+4
#$deferred = new DeferList;
#$deferred->addBoth($add, array(4));
#$deferred->addBoth($printsome);
#$deferred->addBoth($sub, array(10, $deferred, $reactor));
#$deferred->addBoth($printsome);
#$deferred->addBoth($add, array(4));
#$deferred->addBoth($printsome);
#$callback = function($v) use ($deferred) {
#    return $deferred->callback($v);
#};
#$reactor->add(1, $callback, array(2));
#$reactor->loop();
