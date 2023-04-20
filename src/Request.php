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
use Drewlabs\Htr\Contracts\Descriptor;
use Drewlabs\Htr\Testing\TestRunner;
use Drewlabs\Htr\Contracts\BodyDescriptor;
use Drewlabs\Htr\Contracts\RequestInterface;
use Drewlabs\Htr\Utilities\Assert;

final class Request implements ComponentInterface, Arrayable, RequestInterface
{

	use ComponentMixin;

	/**
	 * @var string
	 */
	private $url = null;

	/**
	 * @var array
	 */
	private $headers = [];

	/**
	 * @var Descriptor&Arrayable
	 */
	private $authorization = null;

	/**
	 * @var array
	 */
	private $params = [];

	/**
	 * @var array
	 */
	private $body = [];

	/**
	 * @var string
	 */
	private $method = 'GET';

	/**
	 * Request tests to execute
	 * 
	 * @var TestRunner
	 */
	private $tests = null;

	/**
	 * Request on which the current request depends
	 * 
	 * @var string
	 */
	private $depends_on = null;

	/**
	 * @var array
	 */
	private $cookies = [];

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
		Assert::assertKeyExists($attributes, 'url');
		// #endregion Validate the request attributes

		// #region Create request headers
		$headers = is_array($headers = $attributes['headers'] ?? []) ? $headers : [$headers];
		// #endregion Create request headers
		// #region Create request params
		$params = is_array($params = $attributes['params'] ?? []) ? $params : [$params];
		// #endregion Create request headers

		// #region Set the name property of the request
		$attributes['name'] = $attributes['name'] ?? sprintf("request-%s", $attributes['id'] ?? RandomID::new()->__invoke());
		// #region Set the name property of the request

		/**
		 * @var static $instance
		 */
		$instance = (new static)
			->setMethod($attributes['method'] ?? 'GET')
			->setId(isset($attributes['id']) ? $attributes['id'] : (isset($attributes['name']) ? Slug::new()->__invoke($attributes['name']) : RandomID::new()->__invoke()))
			->setUrl($attributes['url'])
			->setHeaders($headers)
			->setParams($params)
			->setName($attributes['name'])
			->setDescription($attributes['description'] ?? '');

		if (isset($attributes['authorization'])) {
			$instance = $instance->setAuthorization(AuthorizationHeader::fromAttributes($attributes['authorization']));
		}

		if (isset($attributes['body']) && is_array($attributes['body'])) {
			$instance = $instance->setBody($attributes['body']);
		}

		if (isset($attributes['cookies']) && is_array($attributes['cookies'])) {
			$instance = $instance->setCookies($attributes['cookies']);
		}

		// #region Request tests
		$tests = isset($attributes['tests']) ? (is_array($attributes['tests']) ? $attributes['tests'] : $attributes['tests']) : ['[status] eq 200'];
		$instance->setTests(TestRunner::new($tests));
		// #endregion Request tests

		// #region Set the request dependency graph
		if (isset($attributes['depends_on']) && is_string($attributes['depends_on'])) {
			$instance = $instance->setDependsOn($attributes['depends_on']);
		}
		// #endregion Set the request dependency graph

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
			'method' => $this->method,
			'url' => $this->getUrl(),
			'headers' => array_map(function (Arrayable $header) {
				return $header->toArray();
			}, $this->getHeaders()),
			'cookies' => array_map(function (Arrayable $cookie) {
				return $cookie->toArray();
			}, $this->getCookies()),
			'authorization' => $this->authorization ? $this->authorization->toArray() : null,
			'params' => array_map(function (Arrayable $param) {
				return $param->toArray();
			}, $this->getParams()),
			'body' => array_map(function (Arrayable $header) {
				return $header->toArray();
			}, $this->getBody()),
			'tests' => $this->tests->toArray()
		];
	}

	/**
	 * Set url property value
	 * 
	 * @param string $value
	 *
	 * @return self
	 */
	public function setUrl(string $value)
	{
		# code...
		$this->url = $value;

		return $this;
	}

	/**
	 * Set headers property value
	 * 
	 * @param array $value
	 *
	 * @return self
	 */
	public function setHeaders(array $value)
	{
		# code...
		$value = $this->cleanDescriptorsValue($value);
		Assert::assertIsArrayOfArray($value);
		$this->headers = array_map(function ($item) {
			return RequestHeader::fromAttributes($item);
		}, $value);

		return $this;
	}

	/**
	 * Set authorization property value
	 * 
	 * @param Descriptor $value
	 *
	 * @return self
	 */
	public function setAuthorization(Descriptor $value)
	{
		# code...
		$this->authorization = $value;

		return $this;
	}

	/**
	 * Set params property value
	 * 
	 * @param array $value
	 *
	 * @return self
	 */
	public function setParams(array $value)
	{
		# code...
		$value = $this->cleanDescriptorsValue($value);
		Assert::assertIsArrayOfArray($value);
		$this->params = array_map(function ($item) {
			return RequestParam::fromAttributes($item);
		}, $value);

		return $this;
	}

	/**
	 * Set body property value
	 * 
	 * @param array $value
	 *
	 * @return self
	 */
	public function setBody(array $value)
	{
		# code...
		$value = $this->cleanDescriptorsValue($value);
		Assert::assertIsArrayOfArray($value);
		$this->body = array_map(function ($item) {
			return RequestBodyPart::fromAttributes($item);
		}, $value);

		return $this;
	}

	/**
	 * Get url property value
	 * 
	 *
	 * @return string
	 */
	public function getUrl()
	{
		# code...
		return $this->url;
	}

	/**
	 * Get headers property value
	 * 
	 *
	 * @return Descriptor[]
	 */
	public function getHeaders()
	{
		# code...
		return $this->headers  ?? [];
	}

	/**
	 * Get authorization property value
	 * 
	 *
	 * @return Descriptor
	 */
	public function getAuthorization()
	{
		# code...
		return $this->authorization;
	}

	/**
	 * Get params property value
	 * 
	 *
	 * @return Descriptor[]
	 */
	public function getParams()
	{
		# code...
		return $this->params  ?? [];
	}

	/**
	 * Get body property value
	 * 
	 *
	 * @return BodyDescriptor[]
	 */
	public function getBody()
	{
		# code...
		return $this->body  ?? [];
	}


	/**
	 * Set method property value
	 * 
	 * @param string $value
	 *
	 * @return self
	 */
	public function setMethod(string $value)
	{
		# code...
		$this->method = $value;

		return $this;
	}

	/**
	 * Get method property value
	 * 
	 *
	 * @return string
	 */
	public function getMethod()
	{
		# code...
		return $this->method;
	}

	/**
	 * Set tests property value
	 * 
	 * @param TestRunner $value
	 *
	 * @return self
	 */
	public function setTests(TestRunner $value)
	{
		# code...
		$this->tests = $value;

		return $this;
	}

	/**
	 * Get tests property value
	 * 
	 *
	 * @return TestRunner|null
	 */
	public function getTests()
	{
		# code...
		return $this->tests;
	}

	/**
	 * Set depends_on property value
	 * 
	 * @param string $value
	 *
	 * @return self
	 */
	public function setDependsOn(string $value)
	{
		# code...
		$this->depends_on = $value;

		return $this;
	}

	/**
	 * Get depends_on property value
	 * 
	 *
	 * @return string
	 */
	public function getDependsOn()
	{
		# code...
		return $this->depends_on;
	}

	/**
	 * Set cookies property value
	 * 
	 * @param array $value
	 *
	 * @return self
	 */
	public function setCookies(array $value)
	{
		$value = $this->cleanDescriptorsValue($value);
		Assert::assertIsArrayOfArray($value);
		$this->cookies = array_map(function ($item) {
			return RequestCookie::fromAttributes($item);
		}, $value);
		return $this;
	}

	/**
	 * Get cookies property value
	 * 
	 *
	 * @return array
	 */
	public function getCookies()
	{
		# code...
		return $this->cookies;
	}

	/**
	 * Clean the descriptor and prepares it to pass validation for associative arrays
	 * 
	 * @param array $descriptors 
	 * @return array 
	 */
	private function cleanDescriptorsValue(array $descriptors)
	{
		return iterator_to_array((function () use ($descriptors) {
			foreach ($descriptors as $key => $value) {
				if (is_string($key) && !is_array($value)) {
					yield ['name' => $key, 'value' => $value];
					continue;
				}
				yield $value;
			}
		})());
	}
}
