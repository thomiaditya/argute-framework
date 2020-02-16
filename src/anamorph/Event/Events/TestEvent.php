<?php

namespace Anamorph\Event\Events;

use Anamorph\Covenant\Event\Event;

class TestEvent extends Event
{
    const NAME = 'test.event';

    /**
     * Container.
     *
     * @var \Anamorph\Covenant\Container\Container
     */
    protected $container;

    public function __construct(\Anamorph\Covenant\Container\Container $container)
    {
        $this->container = $container;
    }

    public function show()
    {
        echo 'Show method from test event.';
    }
}