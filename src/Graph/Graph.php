<?php

namespace Drewlabs\Htr\Graph;

use Drewlabs\Htr\Arr;
use Generator;

class Graph
{
    /**
     * @var Node[]
     */
    private $nodes;

    /**
     * Creates class instance
     * 
     * @param array<Nodes> $nodes 
     */
    public function __construct(array $nodes)
    {
        $this->nodes = $nodes;
    }

    /**
     * Creates a new graph instance
     * 
     * @param array $nodes 
     * 
     * @return static 
     */
    public static function new(array $nodes)
    {
        return new self($nodes);
    }

    /**
     * Returns the graph top nodes
     * 
     * @return Node[] 
     */
    public function getTopNodes()
    {
        return array_filter($this->nodes, function (Node $value) {
            return $value->isRoot();
        });
    }

    /**
     * Get adjacent or child nodes of a given node
     * 
     * @param Node $node 
     * @return Generator<int, Node, mixed, void> 
     */
    public function getAdjacentNodes(Node $node)
    {
        foreach ($this->nodes as $n) {
            if (strval($n->parent()) === strval($node->key())) {
                yield $n;
            }
        }
    }

    /**
     * Returns a tree view representation of th graph
     * 
     * @return array<Node> 
     */
    public function tree()
    {
        // Build folders tree structure from a list of folders using the BFS algorithm
        // Group the folders by parent id in order to ease the search algorithm
        $groups = Arr::group($this->nodes, function (Node $node) {
            return $node->parent();
        });

        $nodes_func = function ($index) use ($groups) {
            return $groups[$index] ?? [];
        };

        // Get the child nodes for a provided parent node while the parent node
        // still have child node using recursion algorithm
        $map_func = function (Node $node) use (&$map_func, &$nodes_func) {
            $nodes = $nodes_func($node->key()) ?? [];
            return [$node, array_map(function ($n) use ($map_func) {
                return $map_func($n);
            }, $nodes)];
        };

        // Execute the traversal function through all nodes in the graph
        return  array_values(array_map(function ($node) use ($map_func) {
            return $map_func($node);
        }, $this->getTopNodes()));
    }
}
