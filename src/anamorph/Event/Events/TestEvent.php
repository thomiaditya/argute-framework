<?php

namespace Anamorph\Event\Events;

use Anamorph\Covenant\Event\Event;

class TestEvent extends Event
{
    const NAME = 'test.index';

    public function show()
    {
        return 'Show method from test event.';
    }
}