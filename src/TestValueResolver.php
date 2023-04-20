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

use Drewlabs\Curl\REST\Contracts\ResponseInterface;
use Drewlabs\Curl\REST\Response;

class TestValueResolver
{

	/**
	 * Test request response
	 * 
	 * @var ResponseInterface
	 */
	private $response;

	/**
	 * Creates new class instance
	 * 
	 * @param ResponseInterface $response
	 */
	public static function new(ResponseInterface $response)
	{
		$self = new self;
		$self->response = $response;
		return $self;
	}

	/**
	 * No description
	 * 
	 * @param string $value
	 */
	public function __invoke(string $value)
	{
		$result = $this->startsWith($value, '[body]') ? 1 : ($this->startsWith($value, '[headers]') ? 2 : ($this->startsWith($value, '[status]') ? 3 : 0));
		switch ($result) {
			case 1:
				$key = trim(substr($value, strlen('[body]')));
				return empty($key) ? $this->response->getBody() : ($this->response instanceof Response ? $this->response->get($this->startsWith($key, '.') ? substr($key, 1) : $key) : $this->response->getBody());
			case 2:
				$key = trim(substr($value, strlen('[headers]')));
				return empty($key) ? $this->response->getHeaders() : $this->response->getHeader($this->startsWith($key, '.') ? substr($key, 1) : $key);
				// We return the response status code case the result equals 3
			case 3:
				return $this->response->getStatus();
				// By default we return the value as it's as it does not match any case
			default:
				return $value;
		}
	}

	/**
	 * Checks if string starts with $char
	 * 
	 * @param string $haystack 
	 * @param string $char 
	 * @return bool 
	 */
	private function startsWith(string $haystack, string $char)
	{
		if (version_compare(PHP_VERSION, '8.0.0') >= 0) {
			return str_starts_with($haystack, $char);
		}
		return ('' === $char) || (substr($haystack, 0, strlen($char)) === $char);
	}
}
