<?php

namespace Drewlabs\Htr\Graph;

use Drewlabs\Htr\Graph\Node;

class GraphNode implements Node
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string|null
     */
    private $parent;

    /**
     * @var bool
     */
    private $visited = false;

    /**
     * Creates class instance
     * 
     * @param mixed $value 
     * @param string $key 
     * @param string|null $parent 
     */
    public function __construct($value, string $key, string $parent = null)
    {
        $this->value = $value;
        $this->key = $key;
        $this->parent = $parent;
    }

    public function key()
    {
        return $this->key;
    }

    /**
     * {@inheritDoc}
     * 
     * @return Request 
     */
    public function value()
    {
        return $this->value;
    }

    public function parent()
    {
        return $this->parent;
    }

    public function isRoot()
    {
        return null === $this->parent;
    }

    /**
     * Mark the node as visited
     * 
     * @return self|void 
     */
    public function visit()
    {
        $this->visited = true;
    }

    /**
     * Boolean flag indicating whether the node has been visited
     * 
     * @return bool 
     */
    public function visited()
    {
        return $this->visited;
    }
}
