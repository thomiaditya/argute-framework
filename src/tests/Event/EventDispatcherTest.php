<?php

use Anamorph\Covenant\Application\Application as ApplicationApplication;
use Anamorph\Covenant\Container\Container;
use Anamorph\Event\Events\TestEvent;
use Anamorph\Event\EventDispatcher;
use Anamorph\Event\Listeners\TestListener;
use Anamorph\Event\Subscribers\TestEventSubscriber;
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
        
        $dispatcher->listen('test.event', TestListener::class . "::index", 51);
        $dispatcher->listen('test.event', TestListener::class . "::indexTwo", 52);
        
        $dispatcher->dispatch(TestEvent::NAME, new TestEvent($container));
    }

    /** @test Test the dispatcher with string class name. */
    function listenTheListenerUsingStringDispatcher()
    {
        $container = (new Anamorph\Important\Application\Application)->run(dirname(__DIR__));

        $container->instance(ApplicationApplication::class, $container);
        $container->instance(Container::class, $container);

        $dispatcher = $container->develop(EventDispatcher::class);

        $dispatcher->listen('test.event', [new TestListener, 'index']);

        $dispatcher->dispatch(TestEvent::NAME, TestEvent::class);
    }

    /** @test Test using subscriber. */
    function usingSubcriber()
    {
        $container = (new Anamorph\Important\Application\Application)->run(dirname(__DIR__));

        $container->instance(ApplicationApplication::class, $container);
        $container->instance(Container::class, $container);

        /** @var \Anamorph\Event\EventDispatcher */
        $dispatcher = $container->develop(EventDispatcher::class);

        $dispatcher->subscribe(new TestEventSubscriber);
        
        $dispatcher->dispatch(TestEvent::NAME, TestEvent::class);
    }
}