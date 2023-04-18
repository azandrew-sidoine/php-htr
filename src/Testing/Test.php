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

class Test
{

	/**
	 * Test to evaluate
	 * 
	 * @var string
	 */
	private $test = null;

	/**
	 * Create new class instance
	 * 
	 * @param string $test
	 */
	public function __construct(string $test)
	{
		$this->test = $test;
	}


	/**
	 * Evaluate the test logic and returns a test result
	 * 
	 * @param Closure|null $resolver 
	 * @return bool 
	 */
	public function evaluate(\Closure $resolver = null)
	{
		$resolver = $resolver ?? function() {
			// By default resolve null if no resolver is provided
			return null;
		};
 		$tests = $this->compileTests();
		$output = true;
		foreach ($tests as $test) {
			list($op, $items) = $test;
			$result = $op === 'and' ? array_reduce($items, function ($carry, $current) use ($resolver) {
				$carry = $carry && TestStatement::new($current)->evaluate($resolver);
				return $carry;
			}, true) : array_reduce($items, function ($carry, $current) use ($resolver) {
				$carry = $carry || TestStatement::new($current)->evaluate($resolver);
				return $carry;
			}, false);
			$output = $output && $result;
		}
		return $output;
	}

	/**
	 * Creates new test instance
	 * 
	 * @param string $test
	 *
	 * @return static
	 */
	public static function new(string $test)
	{
		return new self($test);
	}

	/**
	 * Set test property value
	 * 
	 * @param string $value
	 *
	 * @return self
	 */
	public function setTest(string $value)
	{
		# code...
		$this->test = $value;

		return $this;
	}

	/**
	 * Get test property value
	 * 
	 *
	 * @return string
	 */
	public function getTest()
	{
		# code...
		return $this->test;
	}

	/**
	 * Compile the test string into an array
	 * 
	 * @return array 
	 */
	private function compileTests()
	{

		// TODO : Split the test into AND test
		$tests = [];
		// we split by ' AND ' instead of 'AND' to avoid any error in case we evaluate a string
		// containing logical and/AND
		$this->splitTestParams($this->test, ' AND ', $values);
		foreach ($values as $t) {
			$current = [];
			// we split by ' OR ' instead of 'OR' to avoid any error in case we evaluate a string
			// containing logical or/OR
			if (false !== (stripos($t, ' OR '))) {
				$this->splitTestParams($t, ' OR ', $current);
				$tests[] = ['or', $current];
				continue;
			}
			$tests[]  = ['and', [$t]];
		}
		return $tests;
	}

	/**
	 * Split the test part into the logical operator until the logical operator is not found
	 * in the test syntax
	 * 
	 * @param string $test 
	 * @param string $logical 
	 * @param mixed $output 
	 * @return void|string 
	 */
	private function splitTestParams(string $test, string $logical, &$output)
	{
		if (false !== ($pos = stripos($test, $logical))) {
			$logical = substr($test, $pos, strlen($logical));
			$tests = explode($logical, $test);
			foreach ($tests as $t) {
				self::splitTestParams($t, $logical, $output);
			}
			return;
		}
		return $output[] = $test;
	}
}
