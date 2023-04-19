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


interface Compiler
{

	/**
	 * Compile value and return the compiled result
	 * 
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function compile($value);

}