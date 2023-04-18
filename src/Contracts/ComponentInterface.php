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


interface ComponentInterface
{

	/**
	 * Returns the component id property
	 * 
	 *
	 * @return string|int
	 */
	public function getId();

	/**
	 * Returns the component name property
	 * 
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Returns the component description property
	 * 
	 *
	 * @return string
	 */
	public function getDescription();

}