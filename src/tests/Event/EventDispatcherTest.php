<?php

use Anamorph\Covenant\Container\Container as ContainerAlias;
use Anamorph\Event\Dispatchs\TestEvent;
use Anamorph\Event\EventDispatcher;
use Anamorph\Event\Listeners\TestListener;
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

        $index = $dispatcher->dispatch(TestEvent::NAME, new TestEvent);
        
        $this->assertEquals('Show method from test event.', $index);
    }

    /** @test Test the dispatcher with string class name. */
    function listenTheListenerUsingStringDispatcher()
    {
        $container = new Anamorph\Important\Application\Application;

        $container->instance(ContainerAlias::class, $container);

        $dispatcher = $container->develop(EventDispatcher::class);

        $dispatcher->listen('test.event', [new TestListener, 'index']);

        $index = $dispatcher->dispatch(TestEvent::NAME, TestEvent::class);

        $this->assertEquals('Show method from test event.', $index);
    }
}