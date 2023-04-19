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

use Closure;
use Drewlabs\Htr\Contracts\Arrayable;
use Drewlabs\Htr\Contracts\ComponentInterface;
// use Drewlabs\Htr\Contracts\RepositoryInterface;
use Drewlabs\Htr\Exceptions\ConfigurationException;

class Project implements Arrayable
{

	/**
	 * List of project components
	 * 
	 * @var array
	 */
	private $components = null;

	/**
	 * Project version
	 * 
	 * @var string
	 */
	private $version = '0.1.0';

	/**
	 * 
	 * @var string
	 */
	private $name;

	/**
	 * Create new class instance
	 * 
	 * @param array $components
	 * @param string $name
	 * @param string $version
	 */
	public function __construct(array $components, string $name, string $version = "0.1.0")
	{
		# code...
		$this->components = $components;
		$this->name = $name;
		$this->version = $version ?? '0.1.0';
	}

	/**
	 * Project environment repository
	 * 
	 *
	 * @return EnvRepository
	 */
	public static function env()
	{
		return EnvRepository::getInstance();
	}

	/**
	 * Creates project instance
	 * 
	 * @param array $env
	 * @param array $components
	 * @param string $name
	 * @param string $version
	 *
	 * @return static
	 */
	public static function make(array $env, array $components, string $name, string $version = "0.1.0")
	{
		// Configure the environent
		EnvRepository::configure($env);

		// Call the project constructor to instanciate the project
		return new self(self::buildComponents($components), $name, $version);
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
		return self::make($attributes['env'] ?? [], $attributes['components'] ?? [], $attributes['name'] ?? 'HTr Request Runner', $attributes['version']);
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
			'version' => $this->version,
			'components' => array_map(function (Arrayable $component) {
				return $component->toArray();
			}, $this->components ?? []),
			'env' => array_map(function (Arrayable $component) {
				return $component->toArray();
			}, $this->env()->values()),
		];
	}

	/**
	 * Set components property value
	 * 
	 * @param array $value
	 *
	 * @return self
	 */
	public function setComponents(array $value)
	{
		# code...
		$this->components = self::buildComponents($value);

		return $this;
	}

	/**
	 * Set version property value
	 * 
	 * @param string $value
	 *
	 * @return self
	 */
	public function setVersion(string $value)
	{
		# code...
		$this->version = $value;

		return $this;
	}

	/**
	 * Get components property value
	 * 
	 *
	 * @return array
	 */
	public function getComponents()
	{
		# code...
		return $this->components;
	}

	/**
	 * Get version property value
	 * 
	 *
	 * @return string
	 */
	public function getVersion()
	{
		# code...
		return $this->version;
	}

	/**
	 * Get name property value
	 * 
	 *
	 * @return string
	 */
	public function getName()
	{
		# code...
		return $this->name;
	}

	/**
	 * Returns the list of project request
	 * 
	 * @param Closure(Request $request):Request|mixed|null $factory
	 * 
	 * @return array 
	 */
	public function getRequests(\Closure $factory = null)
	{
		$factory = $factory ?? function ($value) {
			return $value;
		};
		$components = [];
		$this->getProjectRequests($this->getComponents(), $components, $factory);
		return $components;
	}

	/**
	 * Set the list of project request
	 * 
	 * @param array $components 
	 * @param mixed $output
	 * @param \Closure(Request $request):Request|mixed $factory = null
	 * @return void 
	 */
	private function getProjectRequests(array $components, &$output, \Closure $factory)
	{
		foreach ($components as $component) {
			if ($component instanceof Request) {
				$output[] = $factory($component);
				continue;
			}
			if ($component instanceof RequestDirectory) {
				$this->getProjectRequests($component->getItems(), $output, $factory);
			}
		}
	}

	/**
	 * Returns list of component interface instances
	 * 
	 * @param array $components 
	 * @return array<\Drewlabs\Htr\Contracts\ComponentInterface> 
	 */
	private static function buildComponents(array $components)
	{
		return array_map(function ($component) {
			if ($component instanceof ComponentInterface) {
				return $component;
			}
			if (!is_array($component)) {
				throw new ConfigurationException('Expect component to be an instance of array, ' . (is_object($component) && null !== $component ? get_class($component) : gettype($component)) . ' given');
			}
			$component['type'] = isset($component['type']) ? $component['type'] : (isset($component['url']) && is_string($component['url']) ? ComponentTypes::REQUEST : ComponentTypes::DIRECTORY);
			if (!in_array($component['type'], ComponentTypes::VALUES)) {
				throw new ConfigurationException('Unsupported component type, please make sure your component type attribute equals request|directory');
			}
			return (new ComponentFactory)($component['type'], $component);
		}, $components);
	}
}
