<?php

namespace Anamorph\Covenant\Http;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

interface Request
{
    /**
     * Recap an http request.
     *
     * @return \Anamorph\Http\Request\Request
     */
    public function recap();

    /**
     * Create a clone from state.
     *
     * @param SymfonyRequest $request
     *
     * @return \Anamorph\Http\Request\Request
     */
    public static function createFromState(SymfonyRequest $request);

    /**
     * @inheritDoc
     */
    public function duplicate(array $query = null, array $request = null, array $attributes = null, array $cookies = null, array $files = null, array $server = null);

    /**
     * Give the inserted variable in get method, or post method.
     *
     * @param string $var
     *
     * @return string|array
     */
    public function inserted($var, $default = null);

    /**
     * Get the request method.
     *
     * @return void
     */
    public function method();

    /**
     * Get the request language.
     *
     * @return array
     */
    public function language();
}