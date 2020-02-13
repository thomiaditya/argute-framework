<?php

namespace Anamorph\Covenant\Application;

interface Application
{
    /**
     * Run the application.
     *
     * @return Anamorph\Covenant\Application\Application
     */
    public function run($basePath);

    /**
     * Create singleton defining.
     *
     * @param string $abstract
     * @param string|object|null $concrete
     * 
     * @return void
     */
    public function singletonIf($abstract, $concrete);
} 