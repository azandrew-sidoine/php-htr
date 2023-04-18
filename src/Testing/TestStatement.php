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
use RuntimeException;

class TestStatement
{

	/**
	 * Statement to execute
	 * 
	 * @var string[]
	 */
	private $components = [];

	/**
	 * Create new class instance
	 * 
	 * @param string[] $components
	 */
	public function __construct(array $components)
	{
		# code...
		$this->components = $components;
	}

	/**
	 * Evaluate the statement logic and returns a result
	 * 
	 * @param Closure(string $key):mixed|null $resolver 
	 * @return bool 
	 * @throws RuntimeException 
	 */
	public function evaluate(\Closure $resolver = null)
	{
		$resolver = $resolver ?? function() {
			// By default resolve null if no resolver is provided
			return null;
		};
		if (empty($this->components)) {
			return true;
		}
		return $this->evaluateExpression($resolver, ...$this->components);
	}

	/**
	 * Evaluate an expression and return the result
	 * 
	 * @param Closure(string $key):mixed $resolver 
	 * @param string $left 
	 * @param string|null $op 
	 * @param string|null $right 
	 * @return bool 
	 * @throws RuntimeException 
	 */
	private function evaluateExpression(\Closure $resolver, string $left, string $op = null, string $right = null)
	{
		// Resolve the left hand side of the expression
		$left = (false !== strpos($left, '[')) && (false !== strpos($left, ']')) ? $resolver($left) : $left;

		// Evalue truthy expression
		if ($op === null && $right === null) {
			switch ($left) {
				case 'null':
					return false;
				case 'false':
					return false;
				case '0':
					return false;
				default:
					return boolval($left);
			}
		}

		switch (strtolower($op)) {
			case 'eq':
			case '=':
			case '==':
				return $left == $right;
			case 'ne':
			case '!=':
			case '<>':
				return $left != $right;
			case 'lt':
			case '<':
				return $left < $right;
			case 'lte':
			case '<=':
				return $left <= $right;
			case 'gt':
			case '>':
				return $left < $right;
			case 'gte':
			case '>=':
				return $left >= $right;
			default:
				throw new \RuntimeException("Unsupported operation " . strval($op));
		}
	}

	/**
	 * Creates new statement instance
	 * 
	 * @param string $statement
	 *
	 * @return static
	 */
	public static function new(string $statement)
	{
		$values = array_map(function ($item) {
			return trim($item);
		}, explode(' ', trim($statement)));
		return new self($values);
	}
}
