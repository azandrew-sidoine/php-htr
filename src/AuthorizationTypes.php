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


class AuthorizationTypes
{

	/**
	 * Bearer Token authorization type enumeration
	 * 
	 * @var string
	 */
	public const BEARER = 'bearer';

	/**
	 * Basic Auth authorization type enumeration
	 * 
	 * @var string
	 */
	public const BASIC = 'basic';

	/**
	 * Digest Auth authorization type enumeration
	 * 
	 * @var string
	 */
	public const DIGEST = 'digest';


	/**
	 * List of enumerable values
	 * 
	 * @var string[]
	 */
	public const VALUES = [self::BASIC, self::BEARER, self::DIGEST];
}
