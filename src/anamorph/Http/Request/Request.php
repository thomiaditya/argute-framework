<?php

namespace Anamorph\Http\Request;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request extends SymfonyRequest
{
    /**
     * Recap an http request.
     *
     * @return static
     */
    public static function recap()
    {
       return static::createFromState(SymfonyRequest::createFromGlobals());
    }

    /**
     * Create a clone from state.
     *
     * @param SymfonyRequest $request
     * 
     * @return static
     */
    public static function createFromState(SymfonyRequest $request)
    {
        $newRequest = (new static)->duplicate(
            $request->query->all(), $request->request->all(), $request->attributes->all(),
            $request->cookies->all(), $request->files->all(), $request->server->all()
        );

        return $newRequest;
    }

    /**
     * @inheritDoc
     */
    public function duplicate(array $query = null, array $request = null, array $attributes = null, array $cookies = null, array $files = null, array $server = null)
    {
        return parent::duplicate($query, $request, $attributes, $cookies, $files, $server);
    }
} 