<?php

namespace Anamorph\Event\Dispatchs;

use Anamorph\Covenant\Event\Event;

class TestEvent extends Event
{
    const NAME = 'test.event';

    public function show()
    {
        return 'test.event';
    }
}