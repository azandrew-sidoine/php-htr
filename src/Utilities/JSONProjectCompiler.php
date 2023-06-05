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

class JSONProjectCompiler
{
    /**
     * 
     * @var int
     */
    private $flags;

    /**
     * 
     * @var int
     */
    private $depth;

    /**
     * Creates project compiler instance
     * 
     * @param int $encoding 
     * @param int $linebreak 
     * @return void 
     */
    public function __construct(int $flags = \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES, int $depth = 512)
    {
        $this->flags = $flags ?? \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES;
        $this->depth = $depth ?? 512;
    }

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
        return json_encode($project->toArray(), $this->flags, $this->depth);
    }
}
