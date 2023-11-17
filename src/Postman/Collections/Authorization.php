<?php

namespace Drewlabs\Htr\Postman\Collections;

use Drewlabs\Htr\Contracts\Descriptor;
use Drewlabs\Htr\Postman\Contracts\ComponentInterface;

class Authorization implements ComponentInterface
{
    /**
     * Create authorization class instance
     * 
     * @param string $type 
     * @param AuthorizationMetaData $metadata 
     * @return void 
     */
    public function __construct(
        private string $type = 'bearer',
        private AuthorizationMetaData $metadata
    ) {
    }

    /**
     * Creates instance from HTr authorization instance
     * 
     * @param Descriptor $authorization 
     * @return static 
     */
    public static function fromHTrAutorization(Descriptor $authorization)
    {
        $name = Template::new()->format($authorization->getName());
        $value = Template::new()->format($authorization->getValue());
        $self = new static($name, new AuthorizationMetaData($name, $value));

        return $self;
    }

    public function getComponentName(): string
    {
        return 'auth';
    }

    public function toArray(): array
    {
        return ['type' => $this->type, $this->metadata->getName() => $this->metadata->toArray()];
    }
}
