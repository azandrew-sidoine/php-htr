<?php

declare(strict_types=1);

/*
 * This file is auto generated using the drewlabs/mdl UML model class generator package
 *
 * (c) Sidoine Azandrew <contact@liksoft.tg>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace Drewlabs\Htr;

use Drewlabs\Htr\Graph\DFS_Iterator;
use Drewlabs\Htr\Graph\Graph;
use Drewlabs\Htr\Graph\GraphNode;
use Drewlabs\Htr\Graph\Node;

class Executor
{
    /**
     * @var Project
     */
    private $project;

    /**
     * Creates class instance
     * 
     * @param Project $project 
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Creates new class instance
     * 
     * @param Project $project
     * 
     * @return static 
     */
    public static function new(Project $project)
    {
        return new self($project);
    }

    public function execute()
    {
        $results = [];
        /**
         * @var GraphNode[] $request
         */
        $requests = $this->project->getRequests(function (Request $request) {
            return new GraphNode($request, $request->getId(), $request->getDependsOn());
        });
        $graph = Graph::new($requests);
        foreach ($graph->getTopNodes() as $node) {
            DFS_Iterator::new($graph)->__invoke($node, function (Node $node) use (&$results) {
                // TODO: Execute the request and add response to the result key
                /**
                 * @var Request
                 */
                $request = $node->value();
                $response = RequestExecutor::new($request)->execute($this->project->env());
                // TODO : Test Request response and write to output
            });
        }
    }
}
