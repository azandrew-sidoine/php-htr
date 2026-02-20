<?php

namespace Drewlabs\Htr\Postman\Collections;

use Drewlabs\Htr\Postman\Contracts\ComponentInterface;
use Drewlabs\Htr\Utilities\Uuidv4Factory;
use Random\RandomException;

class Metadata implements ComponentInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * Create metadata class instance
     * 
     * @param string|null $id 
     * @param string $name 
     * @param string $schema 
     * @param mixed $exporter 
     * @return void 
     * @throws RandomException 
     */
    public function __construct(
        ?string $id = null,
        private $name = "Postman Collection",
        private $schema = "https://schema.getpostman.com/json/collection/v2.1.0/collection.json",
        private $exporter = null
    ) {
        $this->id = $id ?? Uuidv4Factory::new()->create();
    }

    public function getComponentName(): string
    {
        return 'info';
    }

    public function toArray(): array
    {
        return [
            "_postman_id" => $this->id,
            "name" => $this->name,
            "schema" => $this->schema,
            "_exporter_id" => $this->exporter
        ];
    }
}
