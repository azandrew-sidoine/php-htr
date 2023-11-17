<?php

namespace Drewlabs\Htr\Postman\Collections;

use Drewlabs\Htr\BodyPart;
use Drewlabs\Htr\Postman\Contracts\ComponentInterface;

class RequestBody implements ComponentInterface
{
    /**
     * Create postman request body instance
     * 
     * @param BodyPart[] $value 
     * @return void 
     */
    public function __construct(private array $value)
    {
    }

    public function getComponentName(): string
    {
        return 'body';
    }

    public function toArray(): array
    {
        return [
            'mode' => 'raw',
            'raw' => json_encode(array_reduce(
                $this->value,
                function (array $carry, BodyPart $body) {
                    $carry[$body->getName()] = $body->getValue();
                    return $carry;
                },
                []
            )),
            'options' => [
                'raw' => [
                    'language' => 'json'
                ]
            ]
        ];
    }
}
