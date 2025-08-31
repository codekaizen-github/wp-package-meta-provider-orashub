<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Validator\Rule\PackageMeta;

use Respect\Validation\Validator;
use Respect\Validation\Rules;
use Respect\Validation\Rules\Core\Simple;

class PluginHeadersArrayRule extends Simple
{
    public function isValid(mixed $input): bool
    {
        if (!is_array($input)) {
            return false;
        }

        return Validator::create(new Rules\Key('Name', new Rules\StringType(), true))
            ->create(new Rules\Key('PluginURI', new Rules\Url(), false))
            ->create(new Rules\Key('Version', new Rules\Version(), false))
            ->create(new Rules\Key('Description', new Rules\StringType(), false))
            ->create(new Rules\Key('Author', new Rules\StringType(), false))
            ->create(new Rules\Key('AuthorURI', new Rules\StringType(), false))
            ->create(new Rules\Key('TextDomain', new Rules\StringType(), false))
            ->create(new Rules\Key('DomainPath', new Rules\StringType(), false))
            ->create(new Rules\Key('Network', new Rules\BoolVal(), false))
            ->create(new Rules\Key('RequiresWP', new Rules\Version(), false))
            ->create(new Rules\Key('RequiresPHP', new Rules\Version(), false))
            ->create(new Rules\Key('UpdateURI', new Rules\Url(), false))
            ->create(new Rules\Key('RequiresPlugins', new Rules\StringType(), false))
            ->isValid($input);
    }
}
