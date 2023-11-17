<?php

namespace Drewlabs\Htr\Postman\Collections;

use Drewlabs\Htr\Postman\Contracts\ComponentInterface;

class Url implements ComponentInterface
{
    /**
     * Creates class instance
     * 
     * @param string $value 
     * @param string|null $host 
     * @return void 
     */
    public function __construct(private string $value, private ?string $host = null, private array $query = [])
    {
    }

    public function getComponentName(): string
    {
        return 'url';
    }

    public function toArray(): array
    {
        $value = Template::new()->format($this->value);
        $host = $this->host;
        if (false === strpos($value, 'http://') && (false === strpos($value, 'https://'))) {
            $pos = strpos($value, "}}");
            $host = substr($value, 0, $pos + 2);
            $url = str_replace($host, 'http://localhost', $value);
        }
        if (null === $host) {
            $host = parse_url($value, PHP_URL_HOST);
        }


        $path = parse_url($url, PHP_URL_PATH);
        $paths = [];
        if ($path) {
            $paths = explode('/', $path);
        }

        $query_str = parse_url($url, PHP_URL_QUERY);
        $query = $this->query;
        if ($query_str) {
            parse_str($query_str, $query);
        }
        return [
            'raw' => $value,
            'host' => $host ? [$host] : [],
            'path' => array_values(array_filter($paths)),
            'query' => array_reduce(array_keys($query), function($carry, $current) use ($query) {
                $carry[] = ['key' => $current, 'value' => $query[$current]];
                return $carry;
            }, [])
        ];
    }
}
