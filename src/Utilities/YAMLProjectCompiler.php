<?php

declare(strict_types=1);

/*
 * This file is part of the Drewlabs package.
 *
 * (c) Sidoine Azandrew <azandrewdevelopper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Drewlabs\Htr\Utilities;

use Drewlabs\Htr\Contracts\Arrayable;

class YAMLProjectCompiler
{
    /**
     * 
     * @var int
     */
    private $encoding;

    /**
     * 
     * @var int
     */
    private $linebreak;

    // /**
    //  * Creates project compiler instance
    //  * 
    //  * @param int $encoding 
    //  * @param int $linebreak 
    //  * @return void 
    //  */
    // public function __construct(int $encoding = YAML_ANY_ENCODING, int $linebreak = YAML_ANY_BREAK)
    // {
    //     $this->encoding = $encoding;
    //     $this->linebreak = $linebreak;
    // }


    /**
     * Create new class instance
     * 
     * @return static 
     */
    public static function new()
    {
        return new static;
    }

    /**
     * Compiles project into yaml string
     * 
     * @param Arrayable $project 
     * @return string 
     */
    #[\ReturnTypeWillChange]
    public function compile(Arrayable $project)
    {
        return preg_replace("/([-]{3}\n)|([.]{3}\n)/", '', \yaml_emit($project->toArray(), $this->encoding, $this->linebreak));
    }
}