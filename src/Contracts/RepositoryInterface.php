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


interface RepositoryInterface
{

	/**
	 * Get an element from the repository
	 * 
	 * @param string $key
	 * @param mixed $default
	 *
	 * @return string|mixed
	 */
	public function get(string $key, $default = null);

}