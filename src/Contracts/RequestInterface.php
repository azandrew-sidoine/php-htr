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

namespace Drewlabs\Htr\Contracts;

use Drewlabs\Htr\Contracts\Descriptor;
use Drewlabs\Htr\Testing\TestRunner;

interface RequestInterface
{

	/**
	 * Get request id property
	 * 
	 *
	 * @return string
	 */
	public function getId();

	/**
	 * Get request url property
	 * 
	 *
	 * @return string
	 */
	public function getUrl();

	/**
	 * Get request headers property
	 * 
	 *
	 * @return Descriptor[]
	 */
	public function getHeaders();

	/**
	 * Get request cookies property
	 * 
	 *
	 * @return Descriptor[]
	 */
	public function getCookies();

	/**
	 * Get request authorization header property
	 * 
	 *
	 * @return Descriptor
	 */
	public function getAuthorization();

	/**
	 * Get request query params property
	 * 
	 *
	 * @return array
	 */
	public function getParams();

	/**
	 * Get request body property
	 * 
	 *
	 * @return BodyDescriptor[]
	 */
	public function getBody();

	/**
	 * Get request method property
	 * 
	 *
	 * @return string
	 */
	public function getMethod();

	/**
	 * Get request tests property
	 * 
	 *
	 * @return TestRunner
	 */
	public function getTests();

}