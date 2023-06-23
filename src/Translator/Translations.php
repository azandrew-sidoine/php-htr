<?php

namespace Drewlabs\Htr\Translator;

use Closure;

class Translations
{
    // TODO: use global instances
    /**
     * @var string[]
     */
    const EN_Us = [
        'description' => '/%s %s',
    ];

    /**
     * @var string[]
     */
    const FR_Fr = [
        'description' => '/%s %s',
    ];

    /**
     * 
     * @var Lang
     */
    private $lang;

    /**
     * Creates class instance
     * 
     * @param string $lang 
     */
    public function __construct(string $lang = 'en')
    {
        switch (strtolower($lang)) {
            case 'en':
                $this->lang = new Lang(static::EN_Us);
                break;
            case 'fr':
                $this->lang = new Lang(static::EN_Us);
                break;

            default:
                $this->lang = new Lang(static::EN_Us);
                break;
        }
    }

    /**
     * Query for translation factory for a given key
     * 
     * @param string $name 
     * @return Closure(mixed ...$args): string|Closure(mixed ...$args): string 
     */
    public function get(string $name)
    {
        return $this->lang->get($name);
    }
}
