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

class ProjectTests
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

    /**
     * Execute the project requests and output result to path
     * 
     * @param string|\Closure $output_path
     * 
     * @return void 
     */
    public function execute($output_path = null)
    {
        $responses = [];
        // Prepares the output headers
        $output = ['Project: ' . $this->project->getName(), 'Version: ' . $this->project->getVersion()];
        /**
         * @var GraphNode[] $request
         */
        $requests = $this->project->getRequests(function (Request $request) {
            return new GraphNode($request, $request->getId(), $request->getDependsOn());
        });

        // #region We create the request graph
        $graph = Graph::new($requests);
        // #endregion We create the request graph

        foreach ($graph->getTopNodes() as $node) {
            DFS_Iterator::new($graph)->__invoke($node, function (Node $node) use (&$responses, &$output) {
                // TODO: Execute the request and add response to the result key
                /**
                 * @var Request
                 */
                $request = $node->value();
                $request_outputs[] = '';
                $request_outputs[] = "---------------------------------------";
                $request_outputs[] = "REQUEST ID: " . $request->getId();
                $request_outputs[] = "---------------------------------------";
                $response = RequestExecutor::new($request)->before(function ($method, $url, $headers, $cookies) use (&$request_outputs) {
                    // #region Write headers to outut
                    $request_outputs[] = "/" . $method . " " . $url;
                    $request_outputs[] = "Request Headers:";
                    foreach ($headers as $key => $value) {
                        $request_outputs[] = "\t\t" . trim($key) . ": " . (is_array($value) ? implode(', ', $value) : $value);
                    }
                    // #region Write headers to outut
                })->execute($this->project->env());

                //#region Write request details to the console
                echo implode(PHP_EOL, $request_outputs) . PHP_EOL;
                //#endregion Write request details to the console

                // We add the response to the list of responses in order to use it value as to resolve placeholders
                $responses[$request->getId()] = $response;

                // #region Add Response result to the request output
                $response_outputs[] = '';
                $response_outputs[] = "Response:";
                $response_outputs[] = "Status: " . strval($response->getStatus());
                $response_outputs[] = "Status Text: " . strval($response->getStatusText());
                $response_outputs[] = "Response Headers:";
                foreach ($response->getHeaders() as $key => $value) {
                    $response_outputs[] = "\t\t" . trim($key) . ": " . (is_array($value) ? implode(', ', $value) : $value);
                }
                // #endregion Add Response result to the request output

                // #region Add Test result to the output
                $testRunner = $request->getTests()->execute(TestValueResolver::new($response));
                $test_outputs[] = '';
                $test_outputs[] = 'Test Results:';
                $testResult = ($testPasses = $testRunner->passes()) ? sprintf("%s", "OK (" . (count($testRunner->getResults())) . " Tests)") : "FAILS (" . (count($testRunner->getResults())) . " Tests , " . (count($testRunner->getFailedTests())) . " Failures)";

                // #region Print Test result to the console
                echo ($testPasses ? Console::normal($testResult, null, 'green') : Console::white($testResult, null, 'red')) . "\n";
                // #endregion Print Test result to the console

                $test_outputs[] = sprintf("\t%s", $testResult);
                if (!$testRunner->passes()) {
                    $test_outputs[] = "Here is the list of failed tests:";
                    foreach ($testRunner->getFailedTests() as $failedTest) {
                        $test_outputs[] = sprintf("- \t%s", $failedTest);
                    }
                }
                // #endregion Add Test result to the output
                // echo implode(PHP_EOL, array_merge($request_outputs, $test_outputs)) . PHP_EOL;
                $output = array_merge($output, $request_outputs, $response_outputs, $test_outputs);
            });
        }
        if (is_string($output_path)) {
            $output_path = function ($output) use ($output_path) {
                file_put_contents($output_path, $output);
            };
        }

        if (is_callable($output_path)) {
            ($output_path)(implode(PHP_EOL, $output));
        }
    }
}
