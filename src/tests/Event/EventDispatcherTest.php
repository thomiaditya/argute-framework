<?php

use Anamorph\Covenant\Container\Container as ContainerAlias;
use Anamorph\Event\Dispatchs\TestEvent;
use Anamorph\Event\EventDispatcher;
use PHPUnit\Framework\TestCase;

require_once 'vendor/autoload.php';

class EventDispatcherTest extends TestCase
{
    /** @test */
    function listenTheListener()
    {
        $container = new Anamorph\Important\Application\Application;

        $container->instance(ContainerAlias::class, $container);

        $dispatcher = $container->develop(EventDispatcher::class);

        $dispatcher->listen('test.event', 'TestListener::index');

        $dispatcher->dispatch(TestEvent::NAME, new TestEvent);
    }
}