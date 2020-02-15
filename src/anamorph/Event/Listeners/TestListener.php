<?php

namespace Anamorph\Event\Listeners;

use Anamorph\Covenant\Event\Event;

class TestListener
{
    /**
     * Index method for testing.
     *
     * @param \Anamorph\Event\Dispatchs\TestEvent $event
     * 
     * @return void
     */
    public function index(Event $event)
    {
        echo $event->show();
    }
}