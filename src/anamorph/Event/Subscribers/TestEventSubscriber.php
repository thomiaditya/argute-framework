<?php

namespace Anamorph\Event\Subscribers;

use Anamorph\Covenant\Event\Event;
use Anamorph\Covenant\Event\Subscriber;
use Anamorph\Event\Events\TestEvent;

class TestEventSubscriber implements Subscriber
{
    public function subscribed()
    {
        return [
            TestEvent::NAME => [
                'index',
                ['index2', -1]
            ]
        ];
    }

    /**
     * Test.
     *
     * @param \Anamorph\Event\Events\TestEvent $event
     * 
     * @return void
     */
    public function index(Event $event)
    {
        echo "\n\n=================From Subscriber=============\n";
        $event->show();
    }

    /**
     * Test.
     *
     * @param \Anamorph\Event\Events\TestEvent $event
     * 
     * @return void
     */
    public function index2(Event $event)
    {
        echo "\n";
        $event->show();
    }
} 