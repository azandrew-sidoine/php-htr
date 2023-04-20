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

use Drewlabs\Curl\REST\Contracts\ResponseInterface;
use Drewlabs\Curl\REST\Exceptions\BadRequestException;
use Drewlabs\Curl\REST\Exceptions\ClientException;
use Drewlabs\Htr\Contracts\RequestInterface;
use Drewlabs\Htr\Graph\DFS_Iterator;
use Drewlabs\Htr\Graph\Graph;
use Drewlabs\Htr\Graph\GraphNode;
use Drewlabs\Htr\Graph\Node;
use Drewlabs\Htr\Testing\TestRunner;
use Drewlabs\Htr\Utilities\Console;
use Drewlabs\Htr\Utilities\ResponseBodyToTable;

class ProjectTests
{
    /**
     * @var Project
     */
    private $project;

    /**
     * @var bool
     */
    private $verbose = false;

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
     * Execute the tests in debug mode. Execution in debug mode output request parameters
     * and test result to the console for each request
     * 
     * @return self 
     */
    public function debug()
    {
        $this->verbose = true;
        return $this;
    }

    /**
     * Execute the project requests and output result to path
     * 
     * @param string|\Closure $then
     * 
     * @return void 
     */
    public function execute($then = null)
    {
        $responses = [];
        // Prepares the output headers
        $output = ['Project: ' . $this->project->getName(), 'Version: ' . $this->project->getVersion()];

        // #region We create the request graph
        $graph = Graph::new($this->project->getRequests(function (Request $request) {
            return new GraphNode($request, $request->getId(), $request->getDependsOn());
        }));
        // #endregion We create the request graph

        foreach ($graph->getTopNodes() as $node) {
            DFS_Iterator::new($graph)->__invoke($node, function (Node $node) use (&$responses, &$output) {
                // #region Initialize output arrays
                $testOutputs = [];
                $responseOutputs = [];
                $requestOutputs = [];
                // #endregion Initialize output arrays

                /**
                 * @var Request
                 */
                $request = $node->value();
                $requestOutputs[] = '';
                $requestOutputs[] = "---------------------------------------";
                $requestOutputs[] = "REQUEST ID: " . $request->getId();
                $requestOutputs[] = "---------------------------------------";
                try {
                    $response = RequestExecutor::new($request)->before(function ($method, $url, $headers, $cookies) use (&$requestOutputs) {
                        // #region Write headers to outut
                        $requestOutputs[] = "/" . $method . " " . $url;
                        $requestOutputs[] = "Request Headers:";
                        foreach ($headers as $key => $value) {
                            $requestOutputs[] = "\t\t" . trim($key) . ": " . (is_array($value) ? implode(', ', $value) : $value);
                        }
                        // #region Write headers to outut

                        // We write request parameters to the output before sending the request
                        $this->log(implode(PHP_EOL, $requestOutputs) . PHP_EOL);
                    })->execute($this->project->env());

                    // We add the response to the list of responses in order to use it value as to resolve placeholders
                    $responses[$request->getId()] = $response;

                    // #region Add Response result to the request output
                    $responseOutputs  = $this->appendResponse($response);
                    // #endregion Add Response result to the request output

                    $testOutputs = $this->executeTests($request, $response);
                } catch (BadRequestException $e) {
                    $response = $e->getResponse();
                    // We add the response to the list of responses in order to use it value as to resolve placeholders
                    $responses[$request->getId()] = $response;

                    // #region Add Response result to the request output
                    $responseOutputs  = $this->appendResponse($response);
                    // #endregion Add Response result to the request output

                    $testOutputs = $this->executeTests($request, $response);
                } catch (ClientException $e) {
                    $this->log(Console::normal('ERROR!', null, 'red') . PHP_EOL);
                    $this->log(Console::red($e->getMessage(), null) . PHP_EOL);
                    $responseOutputs = ['', 'ERROR!', $e->getMessage()];
                }

                $output = array_merge($output, $requestOutputs, $responseOutputs, $testOutputs);
            });
        }
        if (is_string($then)) {
            $then = function ($output) use ($then) {
                file_put_contents($then, $output);
            };
        }

        if (is_callable($then)) {
            ($then)(implode(PHP_EOL, $output));
        }
    }

    /**
     * Run tests on the request response
     * 
     * @param RequestInterface $request 
     * @param ResponseInterface $response 
     * @return string[] 
     */
    private function executeTests(RequestInterface $request, ResponseInterface $response)
    {
        $testRunner = $request->getTests()->execute(TestValueResolver::new($response));
        $outputs = $this->appendTestResult($testRunner, $formatted);
        $outputs[] = '';
        // We log the formatted test results to the output
        $this->log($formatted);

        // Returns the output to the method caller
        return $outputs;
    }

    /**
     * Append request response to the output string
     * 
     * @param ResponseInterface $response 
     * @return string[] 
     */
    private function appendResponse(ResponseInterface $response)
    {
        $output[] = '';
        $output[] = "Response:";
        $output[] = "Status: " . strval($response->getStatus());
        $output[] = "Status Text: " . strval($response->getStatusText());
        $output[] = "Response Headers:";
        foreach ($response->getHeaders() as $key => $value) {
            $output[] = "\t\t" . trim($key) . ": " . (is_array($value) ? implode(', ', $value) : $value);
        }
        $output[] = PHP_EOL;
        $output[] = "Response Body:";
        $table = ResponseBodyToTable::new($response)->__invoke();
        $output[] = $table;
        return $output;
    }

    /**
     * Appends test result to the output string
     * 
     * @param TestRunner $testRunner 
     * @param string $output 
     * @return string[]
     */
    public function appendTestResult(TestRunner $testRunner, &$formattedText)
    {
        $output = ["Test Results:"];
        $testResult = ($testPasses = $testRunner->passes()) ? sprintf("%s", "OK (" . (count($testRunner->getResults())) . " Tests)") : "FAILS (" . (count($testRunner->getResults())) . " Tests , " . (count($testRunner->getFailedTests())) . " Failures)";
        $output[] = sprintf("\t%s", $testResult);
        if (!$testRunner->passes()) {
            $output[] = "Here is the list of failed tests:";
            foreach ($testRunner->getFailedTests() as $failedTest) {
                $output[] = sprintf("- \t%s", $failedTest);
            }
        }
        $formattedText = ($testPasses ? Console::normal($testResult, null, 'green') : Console::white($testResult, null, 'red')) . PHP_EOL;
        return $output;
    }

    /**
     * Log the string to the console
     * 
     * @param string $string 
     * @return void 
     */
    private function log(string $string)
    {
        if ($this->verbose) {
            echo $string;
        }
    }
}
