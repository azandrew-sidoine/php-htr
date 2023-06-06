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
use Drewlabs\Htr\Contracts\RepositoryInterface;
use Drewlabs\Htr\Exceptions\ConfigurationException;
use Drewlabs\Htr\Utilities\JSONProjectCompiler;
use Drewlabs\Htr\Utilities\YAMLProjectCompiler;
use Exception;
use Generator;
use InvalidArgumentException;

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
	 * 
	 * @var string
	 */
	private $id;

	/**
	 * @var RepositoryInterface
	 */
	private $env;

	/**
	 * Create new class instance
	 * 
	 * @param array $components		List of project testable components
	 * @param string $name			Project name
	 * @param string $version		Project version property
	 * @param string|null $id		Project id
	 */
	public function __construct(array $components, string $name, string $version = "0.1.0", string $id = null)
	{
		# code...
		$this->components = $components;
		$this->name = $name;
		$this->version = $version ?? '0.1.0';
		$this->id = $id ?? static::makeProjectId();
	}

	/**
	 * Project environment repository getter and setter
	 * 
	 * @param RepositoryInterface|null $env
	 *
	 * @return EnvRepository
	 */
	public function env(RepositoryInterface $env = null)
	{
		if (null !== $env) {
			$this->env = $env;
		}
		return $this->env;
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
		// Call the project constructor to instanciate the project
		$object = new self(self::buildComponents($components), $name, $version, static::makeProjectId());

		// Configure project environment
		$object->env(EnvRepository::make($env));

		return $object;
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
		return self::make($attributes['env'] ?? [], $attributes['components'] ?? [], $attributes['name'] ?? 'HTr project', $attributes['version']);
	}

	/**
	 * Compile an `HTr` porject instance into 
	 * 
	 * @param string $format 
	 * @return string 
	 * @throws InvalidArgumentException 
	 */
	public function compile(string $format = 'json')
	{
		// Compile the project instance
		switch (strtolower($format)) {
			case 'yml':
			case 'yaml':
				return  YAMLProjectCompiler::new()->compile($this);
			case 'json':
				return  JSONProjectCompiler::new()->compile($this);
			default:
				return  JSONProjectCompiler::new()->compile($this);
		}
	}

	/**
	 * Returns the dictionnary representation of the component
	 *
	 * @return array
	 */
	public function toArray()
	{
		return [
			'project' => $this->id,
			'name' => $this->getName() ?? 'HTr Project',
			'version' => $this->version,
			'env' => array_map(function (Arrayable $component) {
				return $component->toArray();
			}, $this->env()->values()),
			'components' => array_map(function (Arrayable $component) {
				return $component->toArray();
			}, $this->components ?? []),
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
	 * Query for requests matching user provided list or requests
	 * 
	 * @param array $in 
	 * @param Closure|null $factory 
	 * @return array 
	 */
	public function getRequestIn($in = [], \Closure $factory = null)
	{
		$factory = $factory ?? function ($value) {
			return $value;
		};
		$components = [];
		$this->getProjectRequestsIn($this->getComponents(), $components, $factory, $in);
		return $components;
	}

	/**
	 * Query for requests in some given request directories
	 * 
	 * @param array $in 
	 * @param Closure|null $factory 
	 * @return array 
	 */
	public function getRequestWhereDirectoryIn(array $in, \Closure $factory = null)
	{
		$factory = $factory ?? function ($value) {
			return $value;
		};

		// Get the list of requests in the directory
		$components = [];
		$directories = [];
		$this->getDirectories($this->getComponents(), $in, $directories);
		$this->getProjectRequests($directories, $components, $factory);
		return $components;
	}


	/**
	 * Set the list of project request where id or name in the provided array
	 * 
	 * @param array $components 
	 * @param mixed $output
	 * @param \Closure(Request $request):Request|mixed $factory = null
	 * @return void 
	 */
	private function getProjectRequestsIn(array $components, &$output, \Closure $factory, array $in)
	{
		foreach ($components as $component) {
			// filters request using in query in case the in is provided
			if ($component instanceof Request && (in_array($component->getId(), $in) || in_array($component->getName(), $in))) {
				$output[] = $factory($component);
				continue;
			}
			if ($component instanceof RequestDirectory) {
				$this->getProjectRequestsIn($component->getItems(), $output, $factory, $in);
			}
		}
	}

	/**
	 * Set the list of project request
	 * 
	 * @param array|\iterable|\Traversable $components 
	 * @param mixed $output
	 * @param \Closure(Request $request):Request|mixed $factory = null
	 * @return void 
	 */
	private function getProjectRequests($components, &$output, \Closure $factory, $in = [])
	{
		foreach ($components as $component) {
			// filters request using in query in case the in is provided
			if (!empty($in) && $component instanceof Request && (in_array($component->getId(), $in) || in_array($component->getName(), $in))) {
				$output[] = $factory($component);
				continue;
			}
			if ($component instanceof Request) {
				$output[] = $factory($component);
				continue;
			}
			if ($component instanceof RequestDirectory) {
				$this->getProjectRequests($component->getItems(), $output, $factory, $in);
			}
		}
	}


	/**
	 * Get all the directories in the current project
	 * 
	 * @param array $components 
	 * @param array $in
	 * @return Generator<int, RequestDirectory, mixed, void> 
	 */
	private function getDirectories(array $components, array $in, &$output)
	{
		foreach ($components as $component) {
			if ($component instanceof RequestDirectory) {
				if ((in_array($component->getId(), $in) || in_array($component->getName(), $in))) {
					$output[] = $component;
				}
				$this->getDirectories($component->getItems(), $in, $output);
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

	/**
	 * Generates a fake uuid value for the project
	 * 
	 * @return string 
	 * @throws Exception 
	 */
	private static function makeProjectId()
	{
		return sprintf(
			'%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
			random_int(0, 65535),
			random_int(0, 65535),
			random_int(0, 65535),
			random_int(16384, 20479),
			random_int(32768, 49151),
			random_int(0, 65535),
			random_int(0, 65535),
			random_int(0, 65535)
		);
	}
}
