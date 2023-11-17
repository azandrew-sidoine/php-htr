<?php

namespace Drewlabs\Htr\Postman\Collections;

use Drewlabs\Htr\Contracts\Descriptor;
use Drewlabs\Htr\Postman\Contracts\ComponentInterface;

class Request implements ComponentInterface
{
    /**
     * Create request class instance
     * 
     * @param Url $url
     * @param string $name
     * @param string $method 
     * @param Descriptor[] $headers 
     * @param RequestBody|null $body 
     * @return void 
     */
    public function __construct(
        private Url $url,
        private ?Authorization $auth = null,
        private string $method = 'GET',
        private array $headers = [],
        private ?RequestBody $body = null,
        private ?string $name = null,
        private ?string $description = null
    ) {
    }

    public function getComponentName(): string
    {
        return 'request';
    }

    public function toArray(): array
    {
        return array_merge(
            [
                'name' => $this->name,
                'description' => $this->description,
                'method' => $this->method ?? 'GET',
                'header' => array_values(array_reduce($this->headers ?? [], function (array $carry, Descriptor $header) {
                    $carry[] = ['key' => $header->getName(), 'value' => $header->getValue(), 'type' => 'text'];
                    return $carry;
                }, [])),
                $this->url->getComponentName() => $this->url->toArray(),
            ],
            $this->auth ? [$this->auth->getComponentName() => $this->auth->toArray()] : [],
            $this->body ? [$this->body->getComponentName() => $this->body->toArray()] : []
        );
    }
}
