<?php

namespace Drewlabs\Htr\Postman\Contracts;

interface ComponentInterface
{
    /**
     * Returns the component metadata name
     * 
     * @return string 
     */
    public function getComponentName(): string;

    /**
     * Returns component as jsonable dictionary
     * 
     * @return array<string,mixed>
     */
    public function toArray(): array;
}