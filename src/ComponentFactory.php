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

use Drewlabs\Htr\Contracts\ComponentInterface;
use Drewlabs\Htr\Contracts\ComponentInterface as ContractsComponentInterface;
use RuntimeException;

class ComponentFactory
{

	/**
	 * Create the component matching provided type
	 * 
	 * @param string $type
	 * @param array $attributes
	 *
	 * @return ComponentInterface
	 */
	public function __invoke(string $type, array $attributes)
	{
		switch (strtolower($type)) {
			case 'request':
				return Request::fromAttributes($attributes);
			case 'directory':
				return RequestDirectory::fromAttributes($attributes);
			default:
				throw new RuntimeException('Unsupported project component type, supported types are request,directory');
		}
	}

	/**
	 * Create the component matching provided type
	 * 
	 * @param string $type
	 * @param array $attributes
	 *
	 * @return ContractsComponentInterface
	 */
	public function make(string $type, array $attributes)
	{
		return $this->__invoke($type, $attributes);
	}
}
