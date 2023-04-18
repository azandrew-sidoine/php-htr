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

use Drewlabs\Htr\Concerns\DescriptorTrait;
use Drewlabs\Htr\Contracts\BodyDescriptor;
use Drewlabs\Htr\Contracts\Arrayable;

final class RequestBodyPart implements BodyDescriptor, Arrayable
{

	use DescriptorTrait;

	/**
	 * Type property
	 * 
	 * @var string
	 */
	private $type = 'text';

	/**
	 * Create new class instance
	 * 
	 * @param string $name
	 * @param string|mixed $value
	 * @param string $type
	 */
	public function __construct(string $name, $value, string $type = "text")
	{
		# code...
		$this->name = $name;
		$this->value = $value;
		$this->type = $type;
	}

	/**
	 * Creates instance from a list of attributes
	 * 
	 * @param array $attributes
	 *
	 * @return static
	 */
	public static function fromAttributes(array $attributes = [])
	{
		$attributes['type'] = isset($attributes['type']) ? strtolower(strval($attributes['type'])) : BodyTypes::TEXT;
		self::validateAttributes($attributes);
		return new self($attributes['name'], $attributes['value'], $attributes['type']);
	}


	/**
	 * Validate attributes keys exists
	 * 
	 * @param array $attributes 
	 * @return void 
	 * @throws InvalidArgumentException 
	 */
	private static function validateAttributes(array $attributes)
	{
		if (
			isset($attributes['name']) &&
			is_string($attributes['name']) &&
			isset($attributes['value']) &&
			is_scalar($attributes['value']) &&
			isset($attributes['type']) &&
			in_array(strtolower($attributes['type']), BodyTypes::VALUES)
		) {
			return;
		}
		throw new \InvalidArgumentException('$attributes must have the name, the value properties, and a type property of value text|file');
	}

	/**
	 * Returns the dictionnary representation of the component
	 * 
	 *
	 * @return array
	 */
	public function toArray()
	{
		return [
			'name' => $this->name,
			'value' => $this->value,
			'type' => $this->type
		];
	}

	/**
	 * Set type property value
	 * 
	 * @param string $value
	 *
	 * @return self
	 */
	public function setType(string $value)
	{
		# code...
		$this->type = $value;

		return $this;
	}

	/**
	 * Get type property value
	 * 
	 *
	 * @return string
	 */
	public function getType()
	{
		# code...
		return $this->type;
	}
}
