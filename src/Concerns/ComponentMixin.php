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

namespace Drewlabs\Htr\Concerns;


trait ComponentMixin
{

	/**
	 * @var string
	 */
	private $id = null;

	/**
	 * @var string
	 */
	private $name = null;

	/**
	 * @var string
	 */
	private $description = null;

	/**
	 * Set id property value
	 * 
	 * @param string $value
	 *
	 * @return self
	 */
	public function setId(string $value)
	{
		# code...
		$this->id = $value;
		
		return $this;
	}

	/**
	 * Set name property value
	 * 
	 * @param string $value
	 *
	 * @return self
	 */
	public function setName(string $value)
	{
		# code...
		$this->name = $value;
		
		return $this;
	}

	/**
	 * Set description property value
	 * 
	 * @param string $value
	 *
	 * @return self
	 */
	public function setDescription(string $value)
	{
		# code...
		$this->description = $value;
		
		return $this;
	}

	/**
	 * Get id property value
	 * 
	 *
	 * @return string
	 */
	public function getId()
	{
		# code...
		return $this->id;
	}

	/**
	 * Get name property value
	 * 
	 *
	 * @return string
	 */
	public function getName()
	{
		# code...
		return $this->name;
	}

	/**
	 * Get description property value
	 * 
	 *
	 * @return string
	 */
	public function getDescription()
	{
		# code...
		return $this->description;
	}

}