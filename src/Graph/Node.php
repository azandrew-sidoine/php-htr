<?php

namespace Drewlabs\Htr\Graph;

interface Node
{
    /**
     * Returns the current node key
     * 
     * @return string|number 
     */
    public function key();

    /**
     * Returns the value attached to the node
     * 
     * @return mixed 
     */
    public function value();

    /**
     * Returns the current node parent
     * 
     * @return self 
     */
    public function parent();

    /**
     * Boolean flag indicating the current instance is a root node
     * 
     * @return bool 
     */
    public function isRoot();

    /**
     * Mark the node as visited
     * 
     * @return self|void 
     */
    public function visit();

    /**
     * Boolean flag indicating whether the node has been visited
     * 
     * @return bool 
     */
    public function visited();
}
