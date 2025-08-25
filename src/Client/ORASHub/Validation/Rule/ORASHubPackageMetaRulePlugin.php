<?php

namespace CodeKaizen\WPPackageAutoupdater\Client\ORASHub\Validation\Rule;

use Respect\Validation\Validator;
use Respect\Validation\Rules;
use Respect\Validation\Rules\Core\Simple;

class ORASHubPackageMetaRulePlugin extends Simple
{
    public function isValid(mixed $input): bool
    {
        if (!is_object($input)) {
            return false;
        }

        return Validator::create(new Rules\Attribute('version', new Rules\Version(), true))
            ->create(new Rules\Attribute('shortSlug', new Rules\StringType(), true))
            ->create(new Rules\Attribute('fullSlug', new Rules\StringType(), true))->isValid($input);
    }
}
