<?php

namespace Drewlabs\Htr\Postman\Collections;

use Drewlabs\Htr\Concerns\DescriptorTrait;
use Drewlabs\Htr\Contracts\Arrayable;
use Drewlabs\Htr\Contracts\Descriptor;

class Header implements Descriptor, Arrayable
{
    use DescriptorTrait;

    /**
     * Create new class instance
     * 
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value)
    {
        # code...
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Creates header from HTr header instance
     * 
     * @param Descriptor $header 
     * @return static 
     */
    public static function fromHTRHeader(Descriptor $header)
    {
        $self = new static(
            Template::new()->format($header->getName()),
            Template::new()->format($header->getValue())
        );

        $self->setDescription($header->getDescription());

        return $self;
    }
}
