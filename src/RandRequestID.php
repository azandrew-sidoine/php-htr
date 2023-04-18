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


class RandRequestID
{

	/**
	 * Creates class instance
	 */
	private function __construct()
	{
	}

	/**
	 * Creates new class instance
	 * 
	 * @return static
	 */
	public static function new()
	{
        return new self;
	}

	/**
	 * Generates random request id
	 * 
	 * @return string
	 */
	public function __invoke()
	{
		return strval(time()) . strval(rand(1000, 100000));
	}

}