<?php

namespace Drewlabs\Htr\Graph;

class DFS_Iterator
{
    /**
     * Graph instance
     * 
     * @var Graph
     */
    private $graph;

    /**
     * Creates class instance
     * 
     * @param Graph $graph 
     */
    public function __construct(Graph $graph)
    {
        $this->graph = $graph;
    }

    /**
     * Creates new class instance
     * 
     * @param Graph $graph
     * 
     * @return static 
     */
    public static function new(Graph $graph)
    {
        return new self($graph);
    }

    /**
     * Traverse the graph and returns a stack of each element using DFS
     * 
     * @param Node $node 
     * @return void 
     */
    public function __invoke(Node $node, \Closure $callback)
    {
        $stack = [$node];
        while (!empty($stack)) {
            $node = array_pop($stack);

            // Check if node has been visited
            if ($node->visited()) {
                continue;
            }
            // Call provided function on the node
            $callback($node);
            // Mark node as visited
            $node->visit();
            // Add node adjacent nodes to the stack
            foreach ($this->graph->getAdjacentNodes($node) as $n) {
                $stack[] = $n;
            }
        }
        return $stack;
        
    }
}