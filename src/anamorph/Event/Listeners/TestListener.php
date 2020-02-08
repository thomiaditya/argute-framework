<?php

namespace Anamorph\Event\Listeners;

use Anamorph\Covenant\Event\Event;

class TestListener
{
    public function index(Event $event)
    {
        echo $event->show();
    }
}