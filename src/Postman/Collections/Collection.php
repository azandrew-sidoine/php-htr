<?php

namespace Drewlabs\Htr\Postman\Collections;

use Drewlabs\Htr\Contracts\Descriptor;
use Drewlabs\Htr\Project;

class Collection
{
    /**
     * Create collection instance
     * 
     * @param Metadata $metadata 
     * @param CollectionItem[] $component 
     * @param array $variables 
     * @return void 
     */
    public function __construct(
        private Metadata $metadata,
        private array $item,
        private array $variables
    ) {
    }

    /**
     * Create instance from HTR project instance
     * 
     * @param Project $project 
     * @return static 
     */
    public static function fromProject(Project $project)
    {
        return new static(
            new Metadata($project->getProjectId(), $project->getName()),
            array_values(array_map(function ($value) {
                return CollectionItem::fromRequestComponent($value);
            }, $project->getComponents())),
            $project->env()->values()
        );
    }

    public function toArray(): array
    {
        return [
            'info' => $this->metadata->toArray(),
            'item' => array_map(function ($current) {
                return $current->toArray();
            }, $this->item ?? []),
            'variable' => array_reduce(
                $this->variables ?? [],
                function ($carry, Descriptor $env) {
                    $carry[] = ['key' => $env->getName(), 'value' => $env->getValue(), 'type' => 'string'];
                    return $carry;
                },
                []
            )
        ];
    }
}
