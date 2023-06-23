<?php

namespace Drewlabs\Htr\Translator;

class Lang
{
    /**
     * @var array
     */
    private $formats = [];

    /**
     * 
     * @param array $formats 
     * @return void 
     */
    public function __construct(array $formats = [])
    {
        $this->formats = $formats;
    }

    public function get(string $name)
    {
        if (isset($this->formats[$name])) {
            return function(...$args) use ($name) {
                return sprintf($this->formats[$name], ...$args);
            };
        }

        // Returns empty string if the format is not specified
        return function(...$args) {
            return "";
        };
    }
}
