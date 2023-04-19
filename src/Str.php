<?php

namespace Drewlabs\Htr;

class Str
{
    /**
     * Returns the string between the first occurence of both provided characters.
     *
     * @return string
     */
    public static function between(string $character, string $that, string $haystack)
    {
        return self::before($that, self::after($character, $haystack));
    }

    /**
     * Returns the string after the first occurence of the provided $char.
     *
     * @return string
     */
    public static function after(string $char, string $haystack)
    {
        return false !== strpos($haystack, $char) ? substr($haystack, strpos($haystack, $char) + strlen($char)) : '';
    }

    /**
     * Returns the string before the first occurence of the provided $char.
     *
     * @return string
     */
    public static function before(string $char, string $haystack)
    {
        return false !== ($pos = strpos($haystack, $char)) ? mb_substr($haystack, 0, $pos) : '';
    }
}
