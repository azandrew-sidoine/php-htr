<?php

use Faker\Factory;

require __DIR__ . '/vendor/autoload.php';


function parseStringFunction(string $input)
{
    if ((substr_count($input, '(') === 1) && (substr_count($input, ')') === 1) && (false !== ($position = strpos($input, '('))) && (false !== ($position2 = strpos($input, ')'))) && ($input[$position2] === $input[strlen($input) - 1])) {
        $method = trim(substr($input, 0, $position));
        $parameters = array_map(function($param) {
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

function throughFaker(string $value)
{
    if (false !== strpos($value = trim($value), '->')) {
        try {
            $exploded = explode('->', $value);
            $chains = [];
            foreach ($exploded as $v) {
                $chains[] = parseStringFunction($v);
            }
            return array_reduce($chains, function ($carry, $current) use ($value) {
                if (is_string($current)) {
                    throw new RuntimeException(sprintf("Invalid expression -> %s", $value));
                }
                return call_user_func_array([$carry, $current[0]], $current[1]);
            }, Factory::create());
        } catch (\Throwable $e) {
            return $value;
        }
    }
    if (is_string($result = parseStringFunction($value))) {
        return $result;
    }
    return call_user_func_array([Factory::create(), $result[0]], $result[1]);
}


print_r([throughFaker('optional(.9)->randomDigit()')]);

print_r([throughFaker('ean13()')]);