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

namespace Drewlabs\Htr\Testing;

use Closure;
use Drewlabs\Htr\Contracts\Arrayable;

final class TestRunner implements Arrayable
{

	/**
	 * List of test to execute
	 * 
	 * @var array<Test>
	 */
	private $tests = null;

	/**
	 * List of tests results
	 * 
	 * @var array<srtring,bool>
	 */
	private $results = [];

	/**
	 * Create new class instance
	 * 
	 * @param array $tests
	 */
	public function __construct(array $tests)
	{
		# code...
		$this->tests = $tests;
		$this->results = [];
	}

	public static function fromAttributes(array $attributes = [])
	{
		return self::new($attributes);
	}

	public function toArray()
	{
		return array_map(function (Test $test) {
			return $test->getTest();
		}, $this->tests);
	}

	/**
	 * Returns a boolean flag that indicates test successful state
	 * 
	 *
	 * @return bool
	 */
	public function passes()
	{
		if (empty($this->results)) {
			return false;
		}
		foreach ($this->results as $value) {
			if ($value === false) {
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Executes the tests
	 * 
	 * @param Closure|callable|null $resolver
	 *  
	 * @return self 
	 */
	public function execute($resolver = null)
	{
		$resolver = $resolver ?? function() {
			// By default resolve null if no resolver is provided
			return null;
		};
		foreach ($this->getTests() as $test) {
			$this->results[$test->getTest()] = $test->evaluate($resolver);
		}
		return $this;
	}

	/**
	 * Creates new test runner instance
	 * 
	 * @param string[] $tests
	 *
	 * @return static
	 */
	public static function new(array $tests)
	{
		return new self(array_map(function ($test) {
			return $test instanceof Test ? $test : Test::new($test);
		}, array_filter($tests)));
	}

	/**
	 * Set tests property value
	 * 
	 * @param array $value
	 *
	 * @return self
	 */
	public function setTests(array $value)
	{
		# code...
		$this->tests = $value;

		return $this;
	}

	/**
	 * Get tests property value
	 * 
	 *
	 * @return array<Test>
	 */
	public function getTests()
	{
		# code...
		return $this->tests;
	}

	/**
	 * Get result property value
	 * 
	 *
	 * @return array<string,bool>
	 */
	public function getResults()
	{
		# code...
		return $this->results;
	}

	/**
	 * Returns the list of failed tests
	 * 
	 * @return array 
	 */
	public function getFailedTests()
	{
		$output = [];
		foreach ($this->getResults() as $key => $result) {
			if ($result === false) {
				$output[] = $key;
			}
		}
		return $output;
	}
}
