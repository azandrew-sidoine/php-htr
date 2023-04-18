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


interface Descriptor
{

	/**
	 * $name property setter
	 * 
	 * @param string $value
	 */
	public function setName(string $value);

	/**
	 * $name property getter
	 * 
	 */
	public function getName();

	/**
	 * $value property setter
	 * 
	 * @param string|mixed $value
	 */
	public function setValue($value);

	/**
	 * $value property getter
	 * 
	 * @return string|mixed
	 * 
	 */
	public function getValue();

}