<?php

namespace Test\Anamorph;

use Anamorph\Important\Container\Container;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

require_once 'vendor/autoload.php';

class ContainerTest extends \PHPUnit\Framework\TestCase
{
    use MockeryPHPUnitIntegration;

    protected static $num = 0;

    protected function setUp() : void
    {
        $num = ++self::$num;
        echo "\nTest {$num} echoing : ";
    }

    /**
     * Test begin in this line.
     */

    /** @test 1 Defining object with closure. */
    function definingIsWorkWithClosure()
    {
        $container = new Container;

        $container->define(InterfaceTest::class, function($app) {
            return new Foo(new Bar, 'Name');
        });

        $this->assertInstanceOf(Foo::class, $container->get(InterfaceTest::class));
    }
    
    /** @test 2 Defining object with class name. */
    function definingObjectWithClassName()
    {
        $container = new Container;
        
        $container->define(InterfaceTest::class, Foo::class)->withParams([
            'name' => 'Thomi Aditya'
        ]);
        
        $object = $container->get(InterfaceTest::class);
        
        $this->assertInstanceOf(Foo::class, $object);
    }

    /** @test 3 Defining object with array access. */
    function defineObjectWithArrayAccess()
    {
        $container = new Container;

        $container[InterfaceTest::class] = Foo::class;

        $object = $container->get(InterfaceTest::class);

        $this->assertInstanceOf(Foo::class, $object);
    }

    /** @test 4 Defining object with singleton (only initiated once). */
    public function defineObjectWithSingleton()
    {
        $container = new Container;

        $container->singleton(InterfaceTest::class, Foo::class);

        $object = $container->get(InterfaceTest::class);
        $object1 = $container->get(InterfaceTest::class);
        $object2 = $container->get(InterfaceTest::class);
        $object3 = $container->get(InterfaceTest::class);
        $object4 = $container->get(InterfaceTest::class);

        $this->assertInstanceOf(Foo::class, $object);
        $this->assertSame($object1, $object);
        $this->assertSame($object1, $object2);
        $this->assertSame($object1, $object3);
        $this->assertSame($object1, $object4);
    }

    /** @test 5 Defining object with singleton but refering to the instance of an object. */
    function defineObjectWithSingletonInstance()
    {
        $container = new Container;

        $foo = new Foo(new Bar, 'Thomi Aditya');
        
        $container->instance(InterfaceTest::class, $foo);

        $object = $container->get(InterfaceTest::class);
        $object1 = $container->get(InterfaceTest::class);
        $object2 = $container->get(InterfaceTest::class);
        $object3 = $container->get(InterfaceTest::class);
        $object4 = $container->get(InterfaceTest::class);

        $this->assertInstanceOf(Foo::class, $object);
        $this->assertSame($object1, $object);
        $this->assertSame($object1, $object2);
        $this->assertSame($object1, $object3);
        $this->assertSame($object1, $object4);
    }

    /** @test 6 Define object within name, so it can invoke using name just like that. */
    public function defineObjectWithName()
    {
        $container = new Container;

        $container->define('foo', function ($app) {
            return new Foo(new Bar, 'Thomi Aditya');
        });

        $this->assertInstanceOf(Foo::class, $container->get('foo'));
    }

    /** @test 7 Naming an abstract, so it can defined with the name an search for the abstract. */
    function namingAbstract()
    {
        $container = new Container;

        $container->name(InterfaceTest::class, 'foo');
        
        $container->define('bar', function() {
            return new Bar;
        });

        $container->define('foo', function ($app) {
            return new Foo($app['bar'], 'Thomi Aditya');
        });
        
        $obj = $container->develop(Zoo::class);

        $this->assertInstanceOf(Zoo::class, $obj);
    }

    /** @test 8 Defining object with special build (limit an interface to be initiate in which object). */
    public function defineObjectDefiniteToEachClass()
    {
        $container = new Container;

        $container->name(InterfaceTest::class, 'bar');
        $container->name(Bar::class, 'bar');

        $container->instance('bar', new Bar);

        $container->definite(InterfaceTest::class)->when(Goo::class, Zoo::class)->give(Bar::class);

        $obj = $container->develop(Goo::class);
        $obj1 = $container->develop(Zoo::class);

        $this->assertInstanceOf(Goo::class, $obj);
        $this->assertInstanceOf(Zoo::class, $obj1);
    }

    /** @test 9 Naming an abstract 2. */
    function namingAbstract2()
    {
        $container = new Container;

        $container->name(Container::class, 'app');

        $container->instance('app', $container);

        $obj = $container->develop(RouteTest::class);
        $obj1 = $container->develop(RouteTest::class);

        $this->assertInstanceOf(get_class($obj), $obj1);
        $this->assertSame($obj->getApp(), $obj1->getApp());
    }
}




interface InterfaceTest
{

}

class Goo
{
    private $bar;
    public $name;

    public function __construct(InterfaceTest $interface, $name)
    {
        $this->bar = $interface;
        $this->name = $name;

        echo "\nGoo";
        echo "\n\n$name";
    }
}

class RouteTest
{
    protected $app;

    public function __construct(Container $app)
    {
       $this->app = $app;
    }

    public function getApp()
    {
       return $this->app;
    }
}

class Zoo
{
    private $bar;
    public $name;

    public function __construct(InterfaceTest $interface, $name)
    {
        $this->bar = $interface;
        $this->name = $name;

        echo "\nZoo";
        echo "\n\n$name";
    }
}

class Foo implements InterfaceTest
{
    private $bar;
    public $name;

    public function __construct(Bar $bar, $name)
    {
        $this->bar = $bar;
        $this->name = $name;

        echo "\nFoo";
        echo "\n\n$name";
    }
}

class Bar implements InterfaceTest
{
    public function __construct()
    {
        echo "\nBar";
    }
}