<?php

namespace Drewlabs\Htr\Utilities;

use RuntimeException;


/**
 * Expression proxy is simply a class the prepares an a function call passed as string and
 * invoke the compiled expression on the instance which is proxied
 * 
 * @package Drewlabs\Htr\Utilities
 */
class ExpressionProxy
{
    /**
     * @var string
     */
    private $expression;

    /**
     * Creates class instance
     * 
     * @param string $expression 
     */
    public function __construct(string $expression)
    {
        $this->expression = $expression;
    }

    /**
     * Get the callback / method definition from string `$input`
     * 
     * @param string $input 
     * @return (string|array<array-key, mixed>)[]|string 
     */
    private function getCallback(string $input)
    {
        if ((substr_count($input, '(') === 1) && (substr_count($input, ')') === 1) && (false !== ($position = strpos($input, '('))) && (false !== ($position2 = strpos($input, ')'))) && ($input[$position2] === $input[strlen($input) - 1])) {
            $method = trim(substr($input, 0, $position));
            $parameters = array_map(function ($param) {
                // Convert numeric value to corresponding type and leave string values as string
                return is_numeric($param) ? (false !== strpos($param, '.') ? floatval($param) : intval($param)) : $param;

                // Remove empty sring values
            }, array_filter(explode(',', substr($input, strlen($method) + 1, strlen($input) - (strlen($method) + 2))), function ($item) {
                return !empty($item);
            }));
            return [$method, $parameters];
        }
        return $input;
    }

    /**
     * Call the defined expression on the faker instance
     * 
     * @param object $proxied 
     * 
     * @return mixed 
     */
    public function call(object $proxied)
    {
        if (is_string($this->expression) && (false !== strpos($value = trim($this->expression), '->'))) {
            try {
                $callChains = [];
                foreach (explode('->', $value) as $v) {
                    $callChains[] = $this->getCallback($v);
                }
                return array_reduce($callChains, function ($carry, $current) use ($value) {
                    if (is_string($current)) {
                        throw new RuntimeException(sprintf("Invalid expression -> %s", $value));
                    }
                    return call_user_func_array([$carry, $current[0]], $current[1]);
                }, $proxied);
            } catch (\Throwable $e) {
                return $value;
            }
        }
        if (is_string($result = $this->getCallback($value))) {
            return $result;
        }
        return call_user_func_array([$proxied, $result[0]], $result[1]);
    }
}
