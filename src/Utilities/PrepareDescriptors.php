<?php

namespace Drewlabs\Htr\Utilities;

use Closure;
use Generator;

class PrepareDescriptors
{
    /**
     * 
     * @var Closure(mixed $descriptors): Generator<int, mixed, mixed, void>
     */
    private $factory;

    /**
     * Creates class instance
     * 
     * @return void 
     */
    public function __construct()
    {
        $this->factory = function ($descriptors) {
            foreach ($descriptors as $key => $value) {
                if (is_string($key) && !is_array($value)) {
                    yield ['name' => $key, 'value' => $value];
                    continue;
                }
                yield $value;
            }
        };
    }

    /**
     * Call the internal factory function on the list of descriptors
     * 
     * @param array $descriptors 
     * @return array 
     */
    public function call(array $descriptors)
    {
        return iterator_to_array(call_user_func($this->factory, $descriptors));
    }
}
