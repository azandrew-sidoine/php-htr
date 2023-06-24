<?php

namespace Drewlabs\Htr\Compilers\Concerns;

use Drewlabs\Htr\Contracts\RepositoryInterface;
trait ParsesValueTemplate
{
    /**
     * Returns parsed result of the provided $key
     * 
     * @param RepositoryInterface $repository 
     * @param mixed $key
     * 
     * @return string
     */
    public static function parseValue(RepositoryInterface $repository, string $value)
    {
        if (false !== (strpos($value, '[')) && false !== (strpos($value, ']'))) {
            // For each keys in the environment repository, we replace the environment
            // placeholder with the matching value
            foreach ($repository->keys() as $env_key) {
                if (false === strpos($value, "[$env_key]")) {
                    continue;
                }
                $value = str_replace("[$env_key]", $repository->get($env_key, ''), $value);
            }
            return $value;
        }
        return $value;
    }
}
