<?php

namespace Drewlabs\Htr\Contracts;

interface ResponseAware
{
    /**
     * return the response headers
     * 
     * @return Descriptor[] 
     */
    public function getResponseHeaders(): array;

    /**
     * return the response body parts
     * 
     * @return Descriptor[] 
     */
    public function getResponseBody(): array;
}
