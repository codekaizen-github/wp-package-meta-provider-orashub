<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Validator\Rule\Version;

use Respect\Validation\Rules\Core\Simple;

class FlexibleSemanticVersionRule extends Simple
{
    public function isValid(mixed $input): bool
    {
        if (!is_string($input)) {
            return false;
        }
        $pattern = '/^[0-9]+(\.[0-9]+)?(\.[0-9]+)?$/';
        return 1 === preg_match($pattern, $input);
    }
}
