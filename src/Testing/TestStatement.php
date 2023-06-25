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
	 * @param Closure(string $key):mixed|callable|null $resolver 
	 * @return bool 
	 * @throws RuntimeException 
	 */
	public function evaluate($resolver = null)
	{
		$resolver = $resolver ?? function () {
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
	 * @param Closure(string $key):mixed|callable $resolver 
	 * @param string $left 
	 * @param string|null $op 
	 * @param string|null $right 
	 * @return bool 
	 * @throws RuntimeException 
	 */
	private function evaluateExpression($resolver, string $left, string $op = null, string $right = null)
	{
		// Resolve the left hand side of the expression
		$left = (false !== strpos($left, '[')) && (false !== strpos($left, ']')) ? $resolver($left) : $left;
		$right = (false !== strpos($right, '[')) && (false !== strpos($right, ']')) ? $resolver($right) : $right;

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
				return $this->getValue($left) == $this->getValue($right);
			case 'ne':
			case '!=':
			case '<>':
				return $this->getValue($left) != $this->getValue($right);
			case 'lt':
			case '<':
				return $this->getValue($left) < $this->getValue($right);
			case 'lte':
			case '<=':
				return $this->getValue($left) <= $this->getValue($right);
			case 'gt':
			case '>':
				return $this->getValue($left) < $this->getValue($right);
			case 'gte':
			case '>=':
				return $this->getValue($left) >= $this->getValue($right);
			case 'in':
				return false !== array_search($left, is_string($right) ? explode(', ', $right) : $right ?? []);
			case 'has':
				return '__UNKNOWN__' !== $this->arrayGet($left, $right, '__UNKNOWN__');
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
		$component_1 = self::before(' ', $statement);
		$component_2 = self::before(' ', trim(substr($statement, strlen($component_1))));
		$component_3 = substr($statement, strlen($component_1 . ' ' . $component_2));
		return new self([trim($component_1), trim($component_2), trim($component_3)]);
	}

	/**
	 * Get the string before the specified $char
	 * 
	 * @param string $char 
	 * @param string $haystack 
	 * @return string 
	 */
	private static function before(string $char, string $haystack)
	{
        return false !== ($pos = strpos($haystack, $char)) ? substr($haystack, 0, $pos) : '';
	}

	/**
	 * Search for a key in an array value
	 * 
	 * @param array $array 
	 * @param string $name 
	 * @return mixed 
	 */
	private function arrayGet(array $array, string $name, $default = null)
	{
		if (false !== strpos($name, '.')) {
			$keys = explode('.', $name);
			$count = count($keys);
			$index = 0;
			$current = $array;
			while ($index < $count) {
				# code...
				if (null === $current) {
					return null;
				}
				$current = array_key_exists($keys[$index], $current) ? $current[$keys[$index]] : $current[$keys[$index]] ?? null;
				$index += 1;
			}
			return $current ?? $default;
		}
		return array_key_exists($name, $array ?? []) ? $array[$name] : $default;
	}

	/**
	 * return the value with the required type information
	 * @param mixed $value 
	 * @return int|float|bool|string|array
	 */
	private function getValue($value)
	{
		if (is_array($value)) {
			return $value;
		}
	
		if (is_string($value) && is_numeric($value)) {
			return false === strpos($value, '.') ? intval($value) : floatval($value);
		}

		if (is_string($value) && in_array($result = strtolower(trim($value)), ['false', 'true'])) {
			return $result === 'true' ? true : false;
		}

		return $value;
	}
}
