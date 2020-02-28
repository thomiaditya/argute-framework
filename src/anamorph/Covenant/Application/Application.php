<?php

namespace Anamorph\Covenant\Application;

use Anamorph\Covenant\Container\Container;

interface Application extends Container
{
    /**
     * Run the application.
     *
     * @return Anamorph\Covenant\Application\Application
     */
    public static function run($basePath);

    /**
     * Create singleton defining.
     *
     * @param string $abstract
     * @param string|object|null $concrete
     * 
     * @return void
     */
    public function singletonIf($abstract, $concrete);

    /**
     * Get the base namespace.
     *
     * @return string
     */
    public function getBaseNamespace();
} 