<?php

namespace Drewlabs\Htr\Utilities;

/**
 * PHP Colored CLI
 * Used to log strings with custom colors to console using php
 * 
 * Copyright (C) 2013 Sallar Kaboli <sallar.kaboli@gmail.com>
 * MIT Liencesed
 * http://opensource.org/licenses/MIT
 *
 * Original colored CLI output script:
 * (C) Jesse Donat https://github.com/donatj
 * 
 * @method static string bold(string $string, ?bool $newline, ?string $background_color)
 * @method static string dim(string $string, ?bool $newline, ?string $background_color)
 * @method static string black(string $string, ?bool $newline, ?string $background_color)
 * @method static string dark_gray(string $string, ?bool $newline, ?string $background_color)
 * @method static string blue(string $string, ?bool $newline, ?string $background_color)
 * @method static string light_blue(string $string, ?bool $newline, ?string $background_color)
 * @method static string green(string $string, ?bool $newline, ?string $background_color)
 * @method static string light_green(string $string, ?bool $newline, ?string $background_color)
 * @method static string cyan(string $string, ?bool $newline, ?string $background_color)
 * @method static string light_cyan(string $string, ?bool $newline, ?string $background_color)
 * @method static string red(string $string, ?bool $newline, ?string $background_color)
 * @method static string light_red(string $string, ?bool $newline, ?string $background_color)
 * @method static string purple(string $string, ?bool $newline, ?string $background_color)
 * @method static string light_purple(string $string, ?bool $newline, ?string $background_color)
 * @method static string brown(string $string, ?bool $newline, ?string $background_color)
 * @method static string yellow(string $string, ?bool $newline, ?string $background_color)
 * @method static string light_gray(string $string, ?bool $newline, ?string $background_color)
 * @method static string white(string $string, ?bool $newline, ?string $background_color)
 * @method static string normal(string $string, ?bool $newline, ?string $background_color)
 */
class Console
{
    /**
     * @var array<string,string>
     */
    const FOREGROUND_COLORS = array(
        'bold'         => '1',
        'dim'          => '2',
        'black'        => '0;30',
        'dark_gray'    => '1;30',
        'blue'         => '0;34',
        'light_blue'   => '1;34',
        'green'        => '0;32',
        'light_green'  => '1;32',
        'cyan'         => '0;36',
        'light_cyan'   => '1;36',
        'red'          => '0;31',
        'light_red'    => '1;31',
        'purple'       => '0;35',
        'light_purple' => '1;35',
        'brown'        => '0;33',
        'yellow'       => '1;33',
        'light_gray'   => '0;37',
        'white'        => '1;37',
        'normal'       => '0;39',
    );

    /**
     * 
     * @var array<string,string>
     */
    const BACKGROUND_COLORS = array(
        'black'        => '40',   'red'          => '41',
        'green'        => '42',   'yellow'       => '43',
        'blue'         => '44',   'magenta'      => '45',
        'cyan'         => '46',   'light_gray'   => '47',
    );

    const OPTIONS = array(
        'underline'    => '4',    'blink'         => '5',
        'reverse'      => '7',    'hidden'        => '8',
    );

    const EOF = "\n";

    /**
     * Creates and instance of Pear Console_Table.
     *
     * @param integer $align   Default alignment. One of
     *                         CONSOLE_TABLE_ALIGN_LEFT,
     *                         CONSOLE_TABLE_ALIGN_CENTER or
     *                         CONSOLE_TABLE_ALIGN_RIGHT.
     * @param string  $border  The character used for table borders or
     *                         CONSOLE_TABLE_BORDER_ASCII.
     * @param integer $padding How many spaces to use to pad the table.
     * @param string  $charset A charset supported by the mbstring PHP
     *                         extension.
     */
    public static function table(
        $align = CONSOLE_TABLE_ALIGN_LEFT,
        $border = CONSOLE_TABLE_BORDER_ASCII,
        $padding = 1,
        $charset = null
    ) {
        return new Console_Table($align, $border, $padding, $charset);
    }

    /**
     * Creates Pear console table from array
     * 
     * @param array $headers 
     * @param array $data 
     * @return Console_Table 
     */
    public static function tableFrom(
        array $headers,
        array $data
    ) {
        return Console_Table::fromArray($headers, $data);
    }

    /**
     * Logs a string to console.
     * @param  string  $string        Input String
     * @param  string  $color      Text Color
     * @param  boolean $newline    Append EOF?
     * @param  [type]  $background Background Color
     * @return [type]              Formatted output
     */
    public static function log($string = '', $color = 'normal', $newline = true, $background_color = null)
    {
        if (is_bool($color)) {
            $newline = $color;
            $color   = 'normal';
        } elseif (is_string($color) && is_string($newline)) {
            $background_color = $newline;
            $newline          = true;
        }
        $string = $newline ? $string . self::EOF : $string;
        echo self::$color($string, $background_color);
    }

    /**
     * Anything below this point (and its related variables):
     * Colored CLI Output is: (C) Jesse Donat
     * https://gist.github.com/donatj/1315354
     * -------------------------------------------------------------
     */

    /**
     * Catches static calls (Wildcard)
     * @param  string $foreground_color Text Color
     * @param  array  $args             Options
     * @return string                   Colored string
     */
    public static function __callStatic($foreground_color, $args)
    {
        $string         = $args[0];
        $colored_string = "";

        // Check if given foreground color found
        if (isset(self::FOREGROUND_COLORS[$foreground_color])) {
            $colored_string .= "\033[" . self::FOREGROUND_COLORS[$foreground_color] . "m";
        } else {
            die($foreground_color . ' not a valid color');
        }

        array_shift($args);

        foreach ($args as $option) {
            // Check if given background color found
            if (isset(self::BACKGROUND_COLORS[$option])) {
                $colored_string .= "\033[" . self::BACKGROUND_COLORS[$option] . "m";
            } elseif (isset(self::OPTIONS[$option])) {
                $colored_string .= "\033[" . self::OPTIONS[$option] . "m";
            }
        }

        // Add string and end coloring
        $colored_string .= $string . "\033[0m";

        return $colored_string;
    }

    /**
     * Plays a bell sound in console (if available)
     * @param  integer $count Bell play count
     * @return string         Bell play string
     */
    public static function bell($count = 1)
    {
        echo str_repeat("\007", $count);
    }
}
