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

use Drewlabs\Htr\Concerns\DescriptorTrait;
use Drewlabs\Htr\Contracts\Descriptor;
use Drewlabs\Htr\Contracts\Arrayable;

class Env implements Descriptor, Arrayable
{
	use DescriptorTrait;

	/**
	 * Create new class instance
	 * 
	 * @param string $name
	 * @param string|mixed $value
	 */
	public function __construct(string $name, $value)
	{
		# code...
		$this->name = $name;
		$this->value = $value;
	}

}