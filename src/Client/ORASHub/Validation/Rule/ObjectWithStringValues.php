<?php

namespace CodeKaizen\WPPackageAutoupdater\Client\ORASHub\Validation\Rule;

use Respect\Validation\Rules\Core\Simple;

/**
 * Validates that the input is strictly an object (not an array) with arbitrary keys and string values.
 */
class ObjectWithStringValues extends Simple
{
    /**
     * Validates that the input is an object and all its property values are strings.
     *
     * @param mixed $input The value to validate
     * @return bool True if valid, false otherwise
     */
    public function isValid(mixed $input): bool
    {
        // Check if input is an object (not an array)
        if (!is_object($input)) {
            return false;
        }

        // If it's an empty object, it's valid
        if (count(get_object_vars($input)) === 0) {
            return true;
        }

        // Check if all values are strings
        foreach (get_object_vars($input) as $value) {
            if (!is_string($value)) {
                return false;
            }
        }

        return true;
    }
}
