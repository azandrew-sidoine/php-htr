<?php

namespace Drewlabs\Htr\Compilers\Concerns;

use Drewlabs\Htr\Contracts\RepositoryInterface;
trait ParsesValueTemplate
{
    /**
     * Returns parsed result of the provided $key
     * 
     * @param RepositoryInterface $repository 
     * @param string $key
     * 
     * @return string
     */
    public static function parseValue(RepositoryInterface $repository, string $key)
    {
        if (false !== (strpos($key, '[')) && false !== (strpos($key, ']'))) {
            // For each keys in the environment repository, we replace the environment
            // placeholder with the matching value
            foreach ($repository->keys() as $env_key) {
                if (false === strpos($key, "[$env_key]")) {
                    continue;
                }
                $key = str_replace("[$env_key]", $repository->get($env_key, ''), $key);
            }
            return $key;
        }
        return $key;
    }
}
