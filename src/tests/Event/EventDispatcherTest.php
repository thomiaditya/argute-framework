<?php

use Anamorph\Covenant\Application\Application as ApplicationApplication;
use Anamorph\Event\Events\TestEvent;
use Anamorph\Event\EventDispatcher;
use Anamorph\Event\Listeners\TestListener;
use PHPUnit\Framework\TestCase;

require_once 'vendor/autoload.php';

class EventDispatcherTest extends TestCase
{
    /** @test */
    function listenTheListener()
    {
        $container = (new Anamorph\Important\Application\Application)->run(dirname(__DIR__));

        $container->instance(ApplicationApplication::class, $container);

        $dispatcher = $container[EventDispatcher::class];
        
        $dispatcher->listen('test.event', 'TestListener::index');

        $dispatcher->dispatch(TestEvent::NAME, new TestEvent);
    }

    /** @test Test the dispatcher with string class name. */
    function listenTheListenerUsingStringDispatcher()
    {
        $container = (new Anamorph\Important\Application\Application)->run(dirname(__DIR__));

        $container->instance(ApplicationApplication::class, $container);

        $dispatcher = $container->develop(EventDispatcher::class);

        $dispatcher->listen('test.event', [new TestListener, 'index']);

        $dispatcher->dispatch(TestEvent::NAME, TestEvent::class);
    }
}