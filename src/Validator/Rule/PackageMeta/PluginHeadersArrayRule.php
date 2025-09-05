<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Validator\Rule\PackageMeta;

use CodeKaizen\WPPackageMetaProviderLocal\Validator\Rule\Version\FlexibleSemanticVersionRule;
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

        return Validator::create(new Rules\AllOf(
            new Rules\Key('Name', new Rules\StringType(), true),
            new Rules\Key('PluginURI', new Rules\Url(), false),
            new Rules\Key('Version', new FlexibleSemanticVersionRule(), false),
            new Rules\Key('Description', new Rules\StringType(), false),
            new Rules\Key('Author', new Rules\StringType(), false),
            new Rules\Key('AuthorURI', new Rules\StringType(), false),
            new Rules\Key('TextDomain', new Rules\StringType(), false),
            new Rules\Key('DomainPath', new Rules\StringType(), false),
            new Rules\Key('Network', new Rules\BoolVal(), false),
            new Rules\Key('RequiresWP', new FlexibleSemanticVersionRule(), false),
            new Rules\Key('RequiresPHP', new FlexibleSemanticVersionRule(), false),
            new Rules\Key('UpdateURI', new Rules\Url(), false),
            new Rules\Key('RequiresPlugins', new Rules\StringType(), false),
        ))->isValid($input);
    }
}
