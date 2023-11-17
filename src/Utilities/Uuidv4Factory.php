<?php

namespace Drewlabs\Htr\Utilities;

use Random\RandomException;

class Uuidv4Factory
{
    /**
     * Creates the uuid factory instance
     * 
     * @return static 
     */
    public static function new()
    {
        return new static;
    }

    /**
     * Create uuid v4 string value
     * 
     * @return string 
     * @throws RandomException 
     */
    public function create()
    {
        return sprintf(
			'%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
			random_int(0, 65535),
			random_int(0, 65535),
			random_int(0, 65535),
			random_int(16384, 20479),
			random_int(32768, 49151),
			random_int(0, 65535),
			random_int(0, 65535),
			random_int(0, 65535)
		);
    }

}