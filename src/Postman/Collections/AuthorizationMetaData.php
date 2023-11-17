<?php

namespace Drewlabs\Htr\Postman\Collections;

class AuthorizationMetaData
{
    /**
     * Creates bearer authorization instance
     * 
     * @param string $name 
     * @param string $value 
     * @param string $type 
     * @return void 
     */
    public function __construct(
        private string $name,
        private string $value,
        private string $type = 'string'
    ) {
    }

    /**
     * Returns the authorization metadata name
     * 
     * @return string 
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    public function toArray(): array
    {
        return ['key' => $this->name, 'value' => $this->value, 'type' => $this->type];
    }
}
