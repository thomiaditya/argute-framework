<?php

namespace Anamorph\Event;

use Anamorph\Covenant\Container\Container;
use Anamorph\Covenant\Event\Dispatcher;

class EventDispatcher implements Dispatcher
{
    protected $container;

    protected $listened;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function listen($event, $listener, $priority = false)
    {
        $this->listened[$event][] = $this->makeListener($listener);
    }

    protected function makeListener(array $listener)
    {
        return function () use ($listener) {
            call_user_func($listener);
        };
    }

    public function dispatch($event)
    {
        return call_user_func(end($this->listened[$event]));
    }
}