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

use Drewlabs\Htr\Concerns\ComponentMixin;
use Drewlabs\Htr\Contracts\ComponentInterface;
use Drewlabs\Htr\Contracts\Arrayable;
use Drewlabs\Htr\Exceptions\ConfigurationException;
use Drewlabs\Htr\Utilities\Assert;

final class RequestDirectory implements ComponentInterface, Arrayable
{

	use ComponentMixin;

	/**
	 * @var array
	 */
	private $items = null;

	/**
	 * Tests property
	 * 
	 * @var string[]
	 */
	private $tests = null;

	/**
	 * Creates instance from a list of attributes
	 * 
	 * @param array $attributes
	 *
	 * @return static
	 */
	public static function fromAttributes(array $attributes = [])
	{
		// #region Validate the request attributes
		Assert::assertKeyExists($attributes, 'name');
		// #endregion Validate the request attributes

		// #region Set the name property of the request
		$attributes['name'] = $attributes['name'] ?? sprintf("request-%s", $attributes['id'] ?? RandomID::new()->__invoke());
		// #region Set the name property of the request

		/**
		 * @var static $instance
		 */
		$instance = (new static)
			->setTests($attributes['tests'] ?? [])
			->setId(isset($attributes['id']) ? $attributes['id'] : (isset($attributes['name']) ? Slug::new()->__invoke($attributes['name']) : RandomID::new()->__invoke()))
			->setName($attributes['name'])
			->setDescription($attributes['description'] ?? '');

		// #region Set the directory items
		$instance = $instance->setItems($attributes['items'] ?? []);
		// #endregion Set the directory items

		// Returns the constructor instance
		return $instance;
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
			'id' => $this->id,
			'name' => $this->name,
			'description' => $this->description,
			'items' => array_map(function (Arrayable $item) {
				return $item->toArray();
			}, $this->items ?? []),
			'tests' => $this->tests
		];
	}

	/**
	 * Set items property value
	 * 
	 * @param array $value
	 *
	 * @return self
	 */
	public function setItems(array $value)
	{
		# code...
		$this->items = $this->buildComponents($value);

		return $this;
	}

	/**
	 * Get items property value
	 * 
	 *
	 * @return array
	 */
	public function getItems()
	{
		# code...
		return $this->items;
	}

	/**
	 * Set tests property value
	 * 
	 * @param string[] $value
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
	 * Returns list of component interface instances
	 * 
	 * @param array $items
	 * 
	 * @return array<\Drewlabs\Htr\Contracts\ComponentInterface> 
	 */
	private function buildComponents(array $items)
	{
		return array_map(function ($item) {
			if ($item instanceof ComponentInterface) {
				return $item;
			}
			if (!is_array($item)) {
				throw new ConfigurationException('Expect item to be an instance of array, ' . (is_object($item) && null !== $item ? get_class($item) : gettype($item)) . ' given');
			}
			$item['type'] = isset($item['type']) ? $item['type'] : (isset($item['url']) && is_string($item['url']) ? ComponentTypes::REQUEST : ComponentTypes::DIRECTORY);
			if (!in_array($item['type'], ComponentTypes::VALUES)) {
				throw new ConfigurationException('Unsupported item type, please make sure your item type attribute equals request|directory');
			}
			$item['id'] = $item['id'] ?? sprintf("%s.%s", isset($this->id) ? $this->id : (isset($this->name) ? Slug::new()->__invoke($this->name) : RandomID::new()->__invoke()), (isset($item['name']) ? Slug::new()->__invoke($item['name']) : RandomID::new()->__invoke()));
			$item['tests'] = $item['tests'] ?? $this->tests;
			return (new ComponentFactory)($item['type'], $item);
		}, $items);
	}
}
