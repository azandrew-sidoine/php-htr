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

use Drewlabs\Htr\Exceptions\AssertionException;

class Assert
{

    /**
     * Assert that list of provides keys exists on the array and are set
     * 
     * @param array $value 
     * @param mixed $keys 
     * @return void 
     * @throws AssertionException 
     */
    public static function assertKeyExists(array $value, ...$keys)
    {
        $errors = [];
        foreach ($keys as $key) {
            if (!isset($value[$key])) {
                $errors[$key] = true;
                continue;
            }
        }
        if (!empty($errors)) {
            throw AssertionException::keyExists($errors);
        }
    }

    /**
     * Assert that an array only contains array values
     * 
     * @param array $values 
     * @return void 
     * @throws AssertionException 
     */
    public static function assertIsArrayOfArray(array $values)
    {
        $values = array_values($values);
        foreach ($values as $value) {
            if (!is_array($value)) {
                throw new AssertionException('Expect array to contains only array values');
            }
        }
    }

    /**
     * Throws an exception if needle not in the list of values
     * 
     * @param mixed $needle 
     * @param array $values 
     * @return void 
     * @throws AssertionException 
     */
    public static function assertIn($needle, array $values)
    {
        if (!in_array($needle, $values)) {
            throw new AssertionException('Expect ' . strval($needle) . ' to be in ' . implode(', ', $values));
        }
    }
}
