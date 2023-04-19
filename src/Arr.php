<?php

namespace Drewlabs\Htr;

class Arr
{

    /**
     * Group values using the provided closure or key
     * 
     * @param \Traversable|\iterable $values 
     * @param string|\Closure $by 
     * @return array 
     */
    public static function group($values, $by)
    {
        $func =  (!is_string($by) && is_callable($by)) ? $by : function ($value) use ($by) {
            return is_array($value) ?  ($value[$by] ?? null) : (is_object($by) ? $value->{$by} : $value);
        };
        $outputs = [];
        foreach ($values as $key => $value) {
            $keys = $func($value, $key);
            $keys = is_array($keys) ? $keys : [$keys];
            foreach ($keys as $k) {
                if (!array_key_exists($k, $outputs)) {
                    $outputs[$k] = [];
                }
                $outputs[$k][] = $value;
            }
        }
        return $outputs;
    }
}
