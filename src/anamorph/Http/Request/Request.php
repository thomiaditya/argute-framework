<?php

namespace Anamorph\Http\Request;

use Anamorph\Covenant\Http\Request as RequestCovenant;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request extends SymfonyRequest implements RequestCovenant
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
        $content = $request->content;

        $newRequest = (new static)->duplicate(
            $request->query->all(), $request->request->all(), $request->attributes->all(),
            $request->cookies->all(), $request->files->all(), $request->server->all()
        );

        $newRequest->content = $content;

        return $newRequest;
    }

    /**
     * @inheritDoc
     */
    public function duplicate(array $query = null, array $request = null, array $attributes = null, array $cookies = null, array $files = null, array $server = null)
    {
        return parent::duplicate($query, $request, $attributes, $cookies, $files, $server);
    }

    /**
     * Give the inserted variable in get method, or post method.
     *
     * @param string $var
     * 
     * @return string|array
     */
    public function inserted($var, $default = null)
    {
        return $this->query->get($var, $default);
    }

    /**
     * Get method that setted.
     *
     * @return void
     */
    public function method()
    {
        return $this->getMethod();
    }

    /**
     * Get the language.
     *
     * @return void
     */
    public function language()
    {
        return $this->getLanguages();
    }
} 