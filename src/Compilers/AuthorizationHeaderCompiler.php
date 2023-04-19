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

namespace Drewlabs\Htr\Compilers;

use Drewlabs\Htr\Compilers\Concerns\ParsesValueTemplate;
use Drewlabs\Htr\Contracts\Compiler;
use Drewlabs\Htr\Contracts\Descriptor;
use Drewlabs\Htr\Contracts\RepositoryInterface;

class AuthorizationHeaderCompiler implements Compiler
{
	use ParsesValueTemplate;

	/**
	 * @var RepositoryInterface
	 */
	private $env;

	/**
	 * Create new class instance
	 * 
	 * @param RepositoryInterface $env
	 */
	public function __construct(RepositoryInterface $env)
	{
		# code...
		$this->env = $env;
	}

	/**
	 * Create new class instance
	 * 
	 * @param RepositoryInterface $env
	 *
	 * @return static
	 */
	public static function new(RepositoryInterface $env)
	{
		return new self($env);
	}

	/**
	 * Compile value and return the compiled result
	 * 
	 * @param Descriptor $value
	 *
	 * @return array<string,string>
	 */
	public function compile($value)
	{
		if (null === $value) {
			return [];
		}
		return ['Authorization' => sprintf("%s %s", ucfirst($value->getName()), self::parseValue($this->env, strval($value->getValue())))];
	}
}
