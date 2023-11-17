<?php

namespace Drewlabs\Htr\Postman\Collections;

class Template
{
    /**
     * Create new template class instance
     * 
     * @return static 
     */
    public static function new()
    {
        return new static;
    }

    /**
     * Format string value by replacing HTr parameter placeholder []
     * with Postman placeholders {{}}
     * 
     * @param string $value 
     * @return mixed 
     */
    public function format(string $value)
    {
        return str_replace("]", "}}", str_replace("[", "{{", $value));
    }
}