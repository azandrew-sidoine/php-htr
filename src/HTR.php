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

class Executor
{
    /**
     * @var Project
     */
    private $project;

    /**
     * Creates class instance
     * 
     * @param Project $project 
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Creates new class instance
     * 
     * @param Project $project
     * 
     * @return static 
     */
    public static function new(Project $project)
    {
        return new self($project);
    }

    public function execute()
    {

        $request = $this->project->getRequests();
        // Execute the request and handle tests

    }
}