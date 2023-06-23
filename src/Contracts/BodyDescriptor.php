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


interface BodyDescriptor extends Descriptor
{

	/**
	 * $type property setter
	 * 
	 * @param string $value
	 */
	public function setType(string $value);

	/**
	 * $type property getter
	 * 
	 */
	public function getType();

	/**
	 * Set the required state of the descriptor
	 * 
	 * @param bool $required
	 * 
	 * @return static 
	 */
	public function setRequired(bool $required);

	/**
	 * returns the required state of the body part
	 * 
	 * @return bool 
	 */
	public function getRequired();

}