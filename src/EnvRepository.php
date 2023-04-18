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

use Drewlabs\Htr\Contracts\RepositoryInterface;
use Drewlabs\Htr\Contracts\Descriptor;

final class EnvRepository implements RepositoryInterface
{

	/**
	 * Repository instance
	 * 
	 * @var static
	 */
	private static $instance = null;

	/**
	 * Repository values
	 * 
	 * @var array<string,Env>
	 */
	private $values = [];

	/**
	 * Creates repository instance
	 * 
	 */
	private function __construct()
	{
	}

	/**
	 * Returns the repository singleton instance
	 *
	 * @return static
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	/**
	 * Configure the repository instance
	 * 
	 * @param array $attributes 
	 * @return static 
	 */
	public static function configure(array $attributes)
	{
		$instance = self::getInstance();
		foreach ($attributes as $key => $value) {
			$value = is_array($value) ? $value : ['name' => $key, 'value' => $value];
			$env = Env::fromAttributes($value);
			$instance->values[$env->getName()] = $env;
		}
		return $instance;
	}

	/**
	 * Get an element from the repository
	 * 
	 * @param string $key
	 * 
	 * @param mixed $default
	 *
	 * @return string|mixed
	 */
	public function get(string $key, $default = null)
	{
		$value = $this->values[$key] ?? null;
		return $value ? $value->getValue() : $default ?? null;
	}

	/**
	 * Returns the list of keys from the repository
	 * 
	 * @return string[] 
	 */
	public function keys()
	{
		return array_keys(self::getInstance()->values);
	}

	/**
	 * Returns all environment variable from the repository
	 * 
	 * @return array<Descriptor>
	 */
	public function values()
	{
		return array_values(self::getInstance()->values);
	}

	/**
	 * Returns a traversable instance of env repository values
	 * 
	 * @return \Traversable<Descriptor>
	 */
	public function getIterator()
	{
		foreach (self::getInstance()->values as $value) {
			yield $value;
		}
	}

}