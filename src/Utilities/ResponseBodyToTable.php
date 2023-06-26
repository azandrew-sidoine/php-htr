<?php

namespace Drewlabs\Htr\Utilities;

use Drewlabs\Curl\REST\Contracts\ResponseInterface;

class ResponseBodyToTable
{
    /**
     * Test request response
     * 
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var int
     */
    const MAX_OUTPUT_FIELDS = 7;

    /**
     * Creates new class instance
     * 
     * @param ResponseInterface $response
     */
    public static function new(ResponseInterface $response)
    {
        $self = new self;
        $self->response = $response;
        return $self;
    }

    /**
     * Returns a string representation of the response body
     * 
     * @return string 
     */
    public function __invoke()
    {
        $body = $this->response->getBody();
        if (null === $body) {
            return '';
        }
        if (is_string($body)) {
            return $body;
        }
        $array = (array)$body;
        $output = [];
        $index = $this->arrayToTable($array, $output);
        if ($index >= self::MAX_OUTPUT_FIELDS) {
            $output[] = ['column' => 'More', 'value' => '...'];
        }
        return Console::tableFrom([], $output)->setBorder(CONSOLE_TABLE_NO_BORDER)->__toString();
    }


    /**
     * Converts response array to table
     * 
     * @param array $array 
     * @param array $output 
     * @param string $prefix 
     * @return int 
     */
    private function arrayToTable(array $array, array &$output, string $prefix = '')
    {
        $index = 0;

        // Drop from execution context for empty object 
        if (empty($array)) {
            return $index;
        }

        // For each value in the array append formatted output to $output array
        foreach ($array as $key => $value) {
            $index++;
            if ($index > self::MAX_OUTPUT_FIELDS) {
                break;
            }
            if (is_array($value) && array_filter($value, 'is_array') === $value) {
                $output[] = ['column' => !empty($prefix) ? "$prefix" ."[$key]" : $key, 'value' =>  sprintf("[ %s, ... ]", $this->arrayToRows(array_values($value)[0] ?? []))];
                continue;
            }
            if (is_array($value)) {
                $this->arrayToTable($value, $output, !empty($prefix) ? "$prefix"."[$key]" : $key);
                continue;
            }
            $output[] = ['column' => !empty($prefix) ? "$prefix"."[$key]" : $key, 'value' => (is_bool($value) || $value === 1 || $value === 0 ? (boolval($value) ? 'TRUE' : 'FALSE') : $value)];
        }
        return $index;
    }

    /**
     * Returns string representation of an array element
     * 
     * @param array $array 
     * @return string 
     */
    private function arrayToRows(array $array)
    {
        $line = [];
        foreach ($array as $key => $value) {
            $line[] = "[$key] => " . (is_array($value) ? '...' : (is_bool($value) ? (boolval($value) ? 'TRUE' : 'FALSE') : $value));
        }
        return sprintf("[ %s ]", implode(', ', $line));
    }
}
