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


class Slug
{

	/**
	 * Creates class instance
	 */
	private function __construct()
	{
		# code...
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
	 * Creates a slug from provided string
	 * 
	 * @param string $value
	 *
	 * @return string
	 */
	public function __invoke(string $value)
	{
        return strtolower(preg_replace('/\s+/', '-', $value));
	}

}